<?php

namespace App\Http\Controllers;

use App\Exports\StockTransferOrderExport;
use App\Models\BranchLocation;
use App\Models\Grn;
use App\Models\GrnProductDetail;
use App\Models\Product;
use App\Models\PurchaseOrderProduct;
use App\Models\Stock;
use App\Models\StockTransferOrder;
use App\Models\StockTransferOrderProduct;
use App\Models\StoRequestScannedProduct;
use App\Models\TransferReason;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use \PDF;

class StockTransferOrderController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;

    public function transferOrderList(Request $request)
    {
        $export = $request->input('export');
        $search = $request->input('search');
        $search_column = $request->input('search_column');
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $sort = $request->input('sort') ?? 'stock_transfer_orders.created_at|desc';

        $stock_source = $request->input('stock_source_id');
        $stock_destination = $request->input('stock_destination_id');
        $transfer_reason_id = $request->input('transfer_reason_id');
        // $transfer_reason = $request->input('transfer_reason');
        $request_status = $request->input('status');

        if ($sort)
            $sort = explode('|', $sort);

        $data = DB::table('stock_transfer_orders');

        if ($request_status)
            $data->whereIn('stock_transfer_orders.status', $request_status);

        $data->join('branch_locations', 'branch_locations.id', '=', 'stock_transfer_orders.stock_source_id');
        $data->join('branch_locations as destination_location', 'destination_location.id', '=', 'stock_transfer_orders.stock_destination_id');

        if ($stock_source && $stock_destination) {
            $data->whereIn('stock_transfer_orders.stock_source_id', $stock_source);
            $data->orWhereIn('stock_transfer_orders.stock_destination_id', $stock_destination);
        } elseif ($stock_source)
            $data->whereIn('stock_transfer_orders.stock_source_id', $stock_source);
        elseif ($stock_destination)
            $data->whereIn('stock_transfer_orders.stock_destination_id', $stock_destination);
        $data->join('transfer_reasons', 'transfer_reasons.id', '=', 'stock_transfer_orders.transfer_reason_id');
        if ($transfer_reason_id)
            $data->whereIn('transfer_reasons.name', $transfer_reason_id);

        $data = $data->select('stock_transfer_orders.id', 'stock_transfer_orders.created_date', 'stock_transfer_orders.sto_no', 'branch_locations.location as source_location', 'destination_location.location as to_location', 'transfer_reasons.name', 'stock_transfer_orders.status')
            ->where(function ($query) use ($search) {
                $query->orWhere('stock_transfer_orders.created_date', 'like', "%$search%");
                $query->orWhere('stock_transfer_orders.sto_no', 'like', "%$search%");
                $query->orWhere('transfer_reasons.name', 'like', "%$search%");
                $query->orWhere('branch_locations.location', 'like', "%$search%");
                $query->orWhere('destination_location.location', 'like', "%$search%");
            })
            ->orderBy($sort[0], $sort[1]);
        if ($export) {
            $data = $data->get();
            if ($data->isEmpty()) return $this->returnError('No data found for export');

            return Excel::download(new StockTransferOrderExport($data->toArray()), 'purchase_order.xlsx');
        } else $data = $data->paginate($per_page, ['*'], 'page', $page);


        $total = $data->total();

        $check_value_exists_table = StockTransferOrder::get()->isEmpty();

        if ($request->ajax()) {
            return [
                'table' => view('pages.stock_management.stock_transfer_order.list_table', compact('data', 'total', 'check_value_exists_table'))->render(),
                'pagination' => view('components.pagination', compact('data'))->render(),
                'total' => $total,
            ];
        }

        $transfer_reasons = TransferReason::select('id', 'name')->whereNotNull('name')->where('status', 1)->orderBy('name', 'asc')->get();
        $delivery_locations = BranchLocation::select('id', 'location')->whereNotNull('location')->where('status', 1)->orderBy('location', 'asc')->get();

        return view('pages.stock_management.stock_transfer_order.index', compact('data', 'total', 'transfer_reasons', 'delivery_locations', 'check_value_exists_table'));
    }

    public function transferOrderCreate(Request $request)
    {
        $transferReasons = TransferReason::select('id', 'name')->where('name', '!=', 'Others')->orderBy('name', 'asc')->get();
        $otherData = TransferReason::select('id', 'name')->where('name', 'Others')->first();
        $transferReasons->push($otherData);
        $locations = BranchLocation::orderBy('location', 'asc')->get();
        $products = Product::all();
        $stoNo = StockTransferOrder::stoNoGenerator();

        return view('pages.stock_management.stock_transfer_order.add_edit', compact('transferReasons', 'locations', 'products', 'stoNo'));

    }

    public function productValidate(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required',
                'stock_source' => 'required|string'
            ]);

            $result = Stock::where('location_id', $request->stock_source)
                ->where('product_id', $request->product_id)
                ->first();

            $openLabelCount = GrnProductDetail::where('location_id', $request->stock_source)
                ->where('product_id', $request->product_id)
                ->where('is_pack_scanned', 0)
                // ->where('is_grn_saved', 1)
                ->where('status', 'open')
                ->count();
            if ($request->stock_source) {
                if ($result) {
                    $validator->after(function ($validator) use ($openLabelCount, $request) {
                        if ($request->quantity == 0) {
                            $validator->errors()->add('available_qty', 'Please enter a quantity of at least 1.');
                        } elseif ($openLabelCount < $request->quantity) {
                            $validator->errors()->add('available_qty', 'Not enough stock. Lower quantity and retry.');
                        }
                    });
                } else {
                    $validator->after(function ($validator) use ($openLabelCount, $request) {
                        $validator->errors()->add('available_qty', 'Not enough stock. Lower quantity and retry.');
                    });
                }
            }
            if ($validator->fails()) {
                return $this->returnError($validator->errors());
            }
            return $this->returnSuccess(["add_data_available" => true], "Stock available!");

        } catch (Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function checkAvailability(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required',
                'stock_source' => 'required|string'
            ]);

            $result = Stock::where('location_id', $request->stock_source)
                ->where('product_id', $request->product_id)
                ->first();

            $openLabelCount = GrnProductDetail::where('location_id', $request->stock_source)
                ->where('product_id', $request->product_id)
                ->where('is_pack_scanned', 0)
                // ->where('is_grn_saved', 1)
                ->where('status', 'open')
                ->count();

            if ($request->stock_source) {
                if ($result) {
                    $validator->after(function ($validator) use ($openLabelCount, $request) {
                        if ($request->quantity == 0) {
                            $validator->errors()->add('available_qty', 'Please enter a quantity of at least 1.');
                        } elseif ($openLabelCount < $request->quantity) {
                            $validator->errors()->add('available_qty', 'Not enough stock. Lower quantity and retry.');
                        }
                    });
                } else {
                    $validator->after(function ($validator) use ($openLabelCount, $request) {
                        $validator->errors()->add('available_qty', 'Not enough stock. Lower quantity and retry.');
                    });
                }
            }

            if ($validator->fails()) {
                return $this->returnError($validator->errors());
            }

            return $this->returnSuccess(["avl_data_available" => true], "Stock available!");
        } catch (Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function create(Request $request)
    {

        try {
            $sto_no = StockTransferOrder::stoNoGenerator();
            $validator = Validator::make($request->all(), [
                'stock_source' => 'required|numeric',
                'stock_destination' => 'required|numeric',
                'sto_no' => 'required|string|max:24',
                'transfer_reason' => 'required|numeric',
                'other_transfer_reason' => 'required_if:transfer_reason_id,3',
                'created_date' => 'required',
            ], [
                'other_transfer_reason.required_if' => 'The other transfer reason field is required when transfer reason is Other'
            ]);

            if ($request->sto_no) {
                $validator->after(function ($validator) use ($request, $sto_no) {
                    if ($request->sto_no != $sto_no) {
                        $validator->errors()->add('auto_no_expired', "Your request id is already used by another user New Id: " . $sto_no);
                    }
                });
            }

            if ($validator->fails()) {
                return $this->returnError($validator->errors());
            }

            $sto_no = StockTransferOrder::stoNoGenerator();

            $sto = StockTransferOrder::create([
                'stock_source_id' => $request->stock_source,
                'stock_destination_id' => $request->stock_destination,
                'sto_no' => $sto_no,
                'transfer_reason_id' => $request->transfer_reason,
                'transfer_reason' => $request->other_transfer_reason,
                'created_date' => $request->created_date,
                'status' => "Ordered",
            ]);
            foreach ($request->product_details as $product_detail) {
                StockTransferOrderProduct::create([
                    "product_id" => $product_detail['product_id'],
                    "sto_no" => $sto_no,
                    "stock_transfer_order_id" => $sto->id,
                    "quantity" => $product_detail['quantity'],
                ]);
            }
            return $this->returnSuccess($sto, "Stock transfer order saved successfully");
        } catch (Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function editStoOrderedProduct(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'product_details' => 'required|array',
                'product_details.*.product_id' => 'required',
                'product_details.*.quantity' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->returnError($validator->errors());
            }

            // All product availability validation

            $sto = StockTransferOrder::find($request->sto_id);

            foreach ($request->product_details as $index => $product_detail) {

                $openLabelCount = GrnProductDetail::where('location_id', $sto->stock_source_id)
                    ->where('product_id', $product_detail['product_id'])
                    ->where('is_pack_scanned', 0)
                    // ->where('is_grn_saved', 1)
                    ->where('status', 'open')
                    ->count();
                $validator->after(function ($validator) use ($openLabelCount, $product_detail, $index) {
                    if ($openLabelCount < $product_detail['quantity']) {
                        $validator->errors()->add("available_qty", "Not enough stock {$product_detail['product_name']} now. Lower quantity and retry.");
                    }
                });
            }

            if ($validator->fails()) {
                return $this->returnError($validator->errors());
            }

            foreach ($request->product_details as $product_detail) {

                $stoProduct = StockTransferOrderProduct::where('stock_transfer_order_id', $request->sto_id)
                    ->where('product_id', $product_detail['product_id'])
                    ->first();
                if ($stoProduct) {
                    $stoProduct->quantity = $product_detail['quantity'];
                    $stoProduct->save();
                }
            }

            return $this->returnSuccess($stoProduct, "Edit Ordered Product successfully");
        } catch (Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function addStoPack(Request $request)
    {

        try {

            $sto = StockTransferOrder::find($request->sto_id);
            $stoProducts = StockTransferOrderProduct::select('id', 'product_id')->where('stock_transfer_order_id', $request->sto_id)->get();
            foreach ($stoProducts as $stoProduct) {
                GrnProductDetail::where('location_id', $sto->stock_source_id)
                    ->where('product_id', $stoProduct['product_id'])
                    ->where('status', 'open')
                    ->where('is_pack_scanned', 1)
                    // ->where('is_grn_saved', 1)
                    ->where('type', 'sto')
                    ->update([
                        'status' => 'packed'
                    ]);
            }
            $sto->status = "Packed";
            $sto->save();
            return $this->returnSuccess($sto, "Packed Order Successfully");
        } catch (Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function addStoTransit(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'shipping_date' => 'required|string',
                'tracking_id' => 'required|string',
                'product_details' => 'required|array',
                'product_details.*.product_id' => 'required',
                'product_details.*.quantity' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->returnError($validator->errors());
            }

            // Change the status of the scanned products

            $stoProducts = StockTransferOrderProduct::select('id', 'product_id')
                ->where('stock_transfer_order_id', $request->sto_id)
                ->get();

            $sto = StockTransferOrder::find($request->sto_id);

            if (!empty($stoProducts)) {
                foreach ($stoProducts as $stoProduct) {
                    GrnProductDetail::where('location_id', $sto->stock_source_id)
                        ->where('product_id', $stoProduct['product_id'])
                        ->where('status', 'packed')
                        // ->where('is_grn_saved', 1)
                        ->where('is_pack_scanned', 1)
                        ->update([
                            'status' => 'transit'
                        ]);
                }
            }

            // change the status of the StockTransferOrder

            $sto->shipping_date = $request->shipping_date;
            $sto->tracking_id = $request->tracking_id;
            $sto->status = "Transit";
            $sto->save();

            foreach ($request->product_details as $product_detail) {

                // Change the status of STO Products

                $stoProduct = StockTransferOrderProduct::where('stock_transfer_order_id', $request->sto_id)
                    ->where('product_id', $product_detail['product_id'])
                    ->first();
                if ($stoProduct) {
                    $stoProduct->status = "Transit";
                    $stoProduct->save();
                }

                // Stock transfer to transit on stock table

                $stockOrder = StockTransferOrder::find($request->sto_id);
                $stock = Stock::where('location_id', $stockOrder->stock_source_id)
                    ->where('product_id', $product_detail['product_id'])
                    ->first();
                if ($stock) {
                    $stock->stock -= $product_detail['scanned_req_quantity'];
                    $stock->transit += $product_detail['scanned_req_quantity'];
                    $stock->save();
                }

            }

            return $this->returnSuccess($sto, "Transit Order Successfully");
        } catch (Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function stoSummary(Request $request)
    {
        $buttonName = null;

        $stoSummary = StockTransferOrder::with(['stockSource:id,location',
            'stockDestination:id,location',
            'transfer_reasons:id,name',
            'stockTransferOrderProduct.product:id,name'])
            ->find($request->sto_order_id);
        if (!$stoSummary) return $this->returnError('Purchase order not found');

        switch ($request->order_status) {

            case "Ordered":
                $buttonName = "Issue Stock";
                return view('pages.stock_management.stock_transfer_order.summary', compact('stoSummary', 'buttonName'));
            case "Packed":
                $buttonName = "Transit";
                return view('pages.stock_management.stock_transfer_order.summary', compact('stoSummary', 'buttonName'));
            case "Transit":
                return view('pages.stock_management.stock_transfer_order.summary', compact('stoSummary', 'buttonName'));

            default:
                return view('pages.stock_management.stock_transfer_order.summary', compact('stoSummary', 'buttonName'));
        }
    }

    public function pack(Request $request)
    {

        $locations = BranchLocation::select('id', 'location')->where('status', 1)->orderBy('location', 'asc')->get();
        $products = Product::all();
        $stoId = $request->sto_id ?? "";

        $stoData = StockTransferOrder::with(['stock_transfer_order_products.product:id,name'])
            ->find($stoId);
        // if ($request->sto_status == "Ordered"){
        //     return view('pages.stock_management.stock_transfer_order.pack_edit', compact('stoData', 'locations', 'products'));
        // }elseif($request->sto_status == "Packed"){
        //     return view('pages.stock_management.stock_transfer_order.pack_add', compact('stoData', 'locations', 'products'));
        // }else
        if ($request->sto_status == "Transit") {
            return view('pages.stock_management.stock_transfer_order.transit_add', compact('stoData', 'locations', 'products'));
        } else {
            return view('pages.stock_management.stock_transfer_order.pack_add', compact('stoData', 'locations', 'products'));
        }

    }

    public function createPack(Request $request)
    {

        $locations = BranchLocation::select('id', 'location')->where('status', 1)->orderBy('location', 'asc')->get();
        $products = Product::all();
        $stoId = $request->sto_id ?? "";

        $stoData = StockTransferOrder::with(['stock_transfer_order_products.product:id,name'])
            ->find($stoId);

        return view('pages.stock_management.stock_transfer_order.pack_edit', compact('stoData', 'locations', 'products'));

    }

    public function scanStoPack(Request $request)
    {

        $stoProductDetails = $stoPackScanProducts = "";
        $stoProductDetails = StockTransferOrderProduct::with('product:id,name')->find($request->sto_product_id);
        $sto = StockTransferOrder::find($stoProductDetails->stock_transfer_order_id);

        $stoPackScanProducts = GrnProductDetail::where('product_id', $request->product_id)
            ->where('location_id', $sto->stock_source_id)
            ->where('status', 'open')
            ->where('is_pack_scanned', 1)
            // ->where('is_grn_saved', 1)
            ->where('type', 'sto')
            ->get();

        return view('pages.stock_management.stock_transfer_order.pack_scan', compact('stoProductDetails', 'stoPackScanProducts'));
    }


    public function scanStoPackProduct(Request $request)
    {
        try {
            DB::beginTransaction();

            $stoProduct = StockTransferOrderProduct::find($request->sto_product_id);
            $sto = StockTransferOrder::find($stoProduct->stock_transfer_order_id);
            $validator = Validator::make($request->all(), [
                'product_code' => 'required|startsWithProductName:' . $stoProduct->product_id . ',|exists:grn_product_details,scanned_product_id,location_id,' . $sto->stock_source_id . ',status,open,is_pack_scanned,0',
                // 'product_qty' => 'required|numeric',
            ], [
                    'product_code.exists' => 'Invalid code',
                ]
            );

            // Validation for Quantity and Qrn quantity

            $validator->after(function ($validator) use ($stoProduct) {
                if ($stoProduct->scanned_req_quantity >= $stoProduct->quantity) {
                    $validator->errors()->add('product_qty', 'Entry exceeding the specified quantity is not allowed.');
                }
            });

            if ($validator->fails()) {
                return $this->returnError($validator->errors());
            }

            $stoGrnScanProduct = GrnProductDetail::where('location_id', $sto->stock_source_id)
                ->where('scanned_product_id', $request->product_code)
                // ->where('is_grn_saved', 1)
                ->where('status', 'open')
                ->first();
            $stoGrnScanProduct->is_pack_scanned = 1;
            $stoGrnScanProduct->type = "sto";
            $stoGrnScanProduct->save();
            // $stoGrnScanProduct = GrnProductDetail::create([
            //     "sto_id" => $request->sto_id,
            //     "sto_product_id" => $request->sto_product_id,
            //     "scanned_req_po_id" => $request->product_code,
            //     // "quantity" => $request->product_qty,
            //     "category" => "packet",
            //     "status" => "packed"
            // ]);

            $stoProducts = StockTransferOrderProduct::findOrFail($request->sto_product_id);
            $stoProducts->last_scanned_req_quantity = $stoProducts->scanned_req_quantity;
            $stoProducts->scanned_req_quantity += 1;
            $stoProducts->save();

            // $purchase_order_grn = Grn::find($request->sto_id);
            // $purchase_order_grn->grn_quantity += $request->product_qty;
            // $purchase_order_grn->save();

            DB::commit();
            return $this->returnSuccess($stoGrnScanProduct);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }


    public function resetStoPack(Request $request)
    {
        try {
            $stoProduct = StockTransferOrderProduct::find($request->sto_product_id);
            $sto = StockTransferOrder::find($stoProduct->stock_transfer_order_id);
            $stoRequestScannedProduct = GrnProductDetail::where('location_id', $sto->stock_source_id)
                ->where('product_id', $request->product_id)
                ->where('status', 'open')
                ->where('is_pack_scanned', 1)
                // ->where('is_grn_saved', 1)
                ->update([
                    'is_pack_scanned' => 0
                ]);
            $stoProducts = StockTransferOrderProduct::findOrFail($request->sto_product_id);
            $stoProducts->scanned_req_quantity = 0;
            $stoProducts->save();
            return $this->returnSuccess($stoRequestScannedProduct);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function pdfGenerator($id)
    {
        $stoData = StockTransferOrder::with(['stockSource:id,location',
            'stockDestination:id,location',
            'transfer_reasons:id,name',
            'stockTransferOrderProduct.product:id,name'])
            ->find($id);
        $fontPaths = [
            public_path('assets/font/NeueMontreal-Medium.otf'),
            public_path('assets/font/NeueMontreal-Regular.otf')
        ];
        $pdf = PDF::loadView('pdf.stock_management.sto', compact('stoData', 'fontPaths'));
        $response = response($pdf->output())->header('Content-Type', 'application/pdf');
        return $response;
    }
}
