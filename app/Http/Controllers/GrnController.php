<?php

namespace App\Http\Controllers;

use App\Exports\GRNExport;
use App\Models\BranchLocation;
use App\Models\GrnRelation;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderGrnScannedProduct;
use App\Models\PurchaseOrderProduct;
use App\Models\Grn;
use App\Models\GrnProductDetail;
use App\Models\PurchaseOrdersGrnScannedProducts;
use App\Models\Stock;
use App\Models\StockTransferOrder;
use App\Models\StockTransferOrderProduct;
use App\Models\StoGrnScannedProduct;
use App\Models\Transporter;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use \PDF;
use PhpParser\Node\Stmt\TryCatch;

class GrnController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;

    public function grnList(Request $request)
    {
        $export = $request->input('export');
        $search = $request->input('search');
        $search_column = $request->input('search_column');
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $sort = $request->input('sort') ?? 'grns.created_at|desc';

        if ($sort) $sort = explode('|', $sort);
        $delivery_location = $request->input('delivery_location');
        $manufacturer = $request->input('manufacturer');
        $data = DB::table('grns');
        $data->LeftJoin('branch_locations', 'branch_locations.id', '=', 'grns.branch_location_id');

        if ($delivery_location)
            $data->whereIn('branch_locations.location', $delivery_location);
        $data->LeftJoin('manufacturers', 'manufacturers.id', '=', 'grns.manufacturer_id');

        if ($manufacturer)
            $data->whereIn('manufacturers.name', $manufacturer);
        $data = $data->LeftJoin('transporters', 'transporters.id', '=', 'grns.transporter_id');
        // ->join('purchase_orders', 'purchase_orders.id', '=', 'grns.purchase_order_id');

        $data = $data->leftJoin('stock_transfer_orders', function ($join) {
            $join->on('stock_transfer_orders.id', '=', 'grns.purchase_order_id');
            // $join->where('grns.type', '=', 'sto');

        });
        $data = $data->LeftJoin('purchase_orders', function ($join) {
            $join->on('purchase_orders.id', '=', 'grns.purchase_order_id');
            // $join->where('grns.type', '=', 'po');
        });
        $data->leftJoin('branch_locations as sender_grn', 'sender_grn.id', '=', 'grns.manufacturer_id');

        $data->where(function ($query) {
            $query->where('grns.type', '=', 'sto')
                ->orWhere('grns.type', '=', 'po');
        });
        $data = $data->select('grns.id', 'purchase_orders.purchase_order_no', 'stock_transfer_orders.sto_no', 'grns.type', 'grns.grn_no', 'sender_grn.location as sender_name', 'branch_locations.location as branch_name',
            'manufacturers.name as manufacturers_name', 'transporters.name as transporters_name', 'grns.created_on',
        )
            ->addSelect(DB::raw('CASE WHEN as_grns.type = "po" THEN as_manufacturers.name ELSE as_sender_grn.location END as sender'))
            ->addSelect(DB::raw('CASE WHEN as_grns.type = "po" THEN as_purchase_orders.purchase_order_no ELSE as_stock_transfer_orders.sto_no END as order_no'))

            ->where(function ($query) use ($search) {
                $query->orWhere('grns.created_at', 'like', "%$search%");
                $query->orWhere('purchase_orders.purchase_order_no', 'like', "%$search%");
                $query->orWhere('manufacturers.name', 'like', "%$search%");
                $query->orWhere('branch_locations.location', 'like', "%$search%");
            })
            ->whereNotNull('grn_no')
            ->orderBy($sort[0], $sort[1]);


        if($export) {
            $data = $data->get();
            if($data->isEmpty()) return $this->returnError('No data found for export');

            return Excel::download(new GRNExport($data->toArray()), 'grn.xlsx');
        } else $data = $data->paginate($per_page, ['*'], 'page', $page);



        $total = $data->total();
        $check_value_exists_table = Grn::get()->isEmpty();

        if ($request->ajax()) {
            return [
                'table' => view('pages.stock_management.grn.grn_table', compact('data', 'total', 'check_value_exists_table'))->render(),
                'pagination' => view('components.pagination', compact('data'))->render(),
                'total' => $total,
            ];
        }

        $manufacturers = Manufacturer::select('name')->whereNotNull('name')->groupBy('name')->get()->pluck('name');
        $delivery_locations = BranchLocation::select('location')->whereNotNull('location')->where('status', 1)->groupBy('location')->get()->pluck('location');

        return view('pages.stock_management.grn.index', compact('data', 'total', 'manufacturers', 'delivery_locations', 'check_value_exists_table'));
    }

    public function createGrn(Request $request)
    {

        $sender_names = Manufacturer::select('id', 'name')->orderBy('name', 'asc')->get();
        $grn_locations = BranchLocation::select('id', 'location')->where('status', 1)->orderBy('location', 'asc')->get();
        $transporter_names = Transporter::select('id', 'name')->orderBy('name', 'asc')->get();
        $order_id = $request->order_id ?? null;

        if ($request->type == "sto") {
            $stoData = StockTransferOrder::with('stock_transfer_order_products')->find($order_id);
            if($request->grn_id){
                if ($stoData && isset($stoData->stock_transfer_order_products)) {
                    foreach ($stoData->stock_transfer_order_products as &$product) {
                        $grn_quantity_currently_scanned_products = \App\Models\GrnProductDetail::where(['grn_id' => $request->grn_id, 'op_product_id' => $product['id'], 'location_id' => $stoData->stock_destination_id, 'status' => 'open'])->get()->count();
                        $product->grn_quantity_currently_scanned_products = isset($grn_quantity_currently_scanned_products) ? $grn_quantity_currently_scanned_products : 0;
                    }
                }
            }
            $grn_no = Grn::grnNoGenerator();
            session(['grn_no' => $grn_no]);

            return view('pages.stock_management.stock_transfer_order.sto_grn_create', compact('stoData', 'sender_names', 'grn_locations', 'transporter_names', 'order_id', 'grn_no'));
        } else if ($request->type == "po") {
            $poData = PurchaseOrder::with('purchase_order_products')->find($order_id);
            if($request->grn_id){
                if ($poData && isset($poData->purchase_order_products)) {
                    foreach ($poData->purchase_order_products as &$product) {
                        $grn_quantity_currently_scanned_products = \App\Models\GrnProductDetail::where(['grn_id' => $request->grn_id, 'op_product_id' => $product['id'], 'location_id' => $poData->delivery_location, 'status' => 'open'])->get()->count();
                        $product->grn_quantity_currently_scanned_products = isset($grn_quantity_currently_scanned_products) ? $grn_quantity_currently_scanned_products : 0;
                    }
                }
            }
            // $all_purchase_orders = PurchaseOrder::select('purchase_order_no', 'id')
            //     ->where('status', 'open')
            //     ->orderBy('id', 'desc')
            //     ->get();
            $grn_no = Grn::grnNoGenerator();
            session(['grn_no' => $grn_no]);
            return view('pages.stock_management.purchase_order.po_grn_create', compact('poData', 'sender_names', 'grn_locations', 'transporter_names', 'order_id', 'grn_no'));
        } else {
            $grnData = "";
            $grn_no = Grn::grnNoGenerator();
            session(['grn_no' => $grn_no]);
            $all_stock_transfer_orders = StockTransferOrder::select('sto_no as purchase_order_no', 'id')
                ->where('status', 'transit')
                ->orderBy('id', 'desc')
                ->get()->toArray();
            $transfer_order = array_map(function ($all_stock_transfer_orders) {
                return $all_stock_transfer_orders + ['type' => 'sto'];
            }, $all_stock_transfer_orders);
            $all_purchase_order = PurchaseOrder::select('purchase_order_no', 'id')
                ->where('status', 'open')
                ->orderBy('id', 'desc')
                ->get()->toArray();
            $purchase_order = array_map(function ($all_purchase_order) {
                return $all_purchase_order + ['type' => 'po'];
            }, $all_purchase_order);
            $all_purchase_orders = array_merge($transfer_order, $purchase_order);
            return view('pages.stock_management.grn.grn_create', compact('grnData', 'sender_names', 'grn_locations', 'transporter_names', 'grn_no', 'all_purchase_orders'));
        }
    }

    public function scanGrn(Request $request)
    {
        $productDetails =  $grnScanProducts = "";
        if ($request->type == "sto") {
            $productDetails = StockTransferOrderProduct::find($request->order_product_id);
            $productDetails['type'] = 'sto';
            $grnScanProducts = GrnProductDetail::where('op_product_id', $request->order_product_id)
                ->where('grn_id', $request->grn_id)
                ->get();
            $grn_no = session('grn_no');
            return view('pages.stock_management.grn.grn_scan', compact('productDetails', 'grnScanProducts', 'grn_no'));
        } else {

            $productDetails = PurchaseOrderProduct::find($request->order_product_id);
            $grnScanProducts = GrnProductDetail::where('op_product_id', $request->order_product_id)
                ->where('grn_id', $request->grn_id)
                ->get();
            $grn_no = session('grn_no');
            return view('pages.stock_management.grn.grn_scan', compact('productDetails', 'grnScanProducts', 'grn_no'));
        }
    }

    public function scanProduct(Request $request)
    {
        try {

            // Log::warning('POGRN : ' . Session::get('UN_SAVED_PO_GRN_ID'));
            // Log::warning('STOGRN : ' . Session::get('UN_SAVED_STO_GRN_ID'));
            DB::beginTransaction();
            if ($request->type == "sto") {

                $stoProduct = StockTransferOrderProduct::find($request->order_product_id);
                $sto = StockTransferOrder::find($stoProduct->stock_transfer_order_id);
                $validator = Validator::make($request->all(), [
                    'product_code' => 'required|startsWithProductName:' . $stoProduct->product_id . ',|exists:grn_product_details,scanned_product_id,location_id,' . $sto->stock_source_id . ',status,transit',
                    // 'product_qty' => 'required|numeric',
                ]);

                // Validation for Quantity and Qrn quantity

                $validator->after(function ($validator) use ($stoProduct) {
                    if ($stoProduct->grn_quantity >= $stoProduct->quantity) {
                        $validator->errors()->add('product_qty', 'Entry exceeding the specified quantity is not allowed.');
                    }
                });

                if ($validator->fails()) {
                    return $this->returnError($validator->errors());
                }

                // Change the status of the sto label to transfer

                $grnProduct = GrnProductDetail::where('location_id', $sto->stock_source_id)
                    ->where('scanned_product_id', $request->product_code)
                    ->where('is_pack_scanned', 1)
                    ->where('status', 'transit')
                    ->first();

                if ($grnProduct) {
                    $grnProduct->status = "transfered";
                    $grnProduct->save();
                }

                // Create new label from other branch
                $stoProduct = StockTransferOrderProduct::find($request->order_product_id);
                $sto = StockTransferOrder::find($stoProduct->stock_transfer_order_id);

                $GrnScanProduct = GrnProductDetail::create([
                    "grn_id" => $request->grn_id,
                    "location_id" => $sto->stock_destination_id,
                    'order_id' => $sto->id,
                    "op_product_id" => $request->order_product_id,
                    "scanned_product_id" => $request->product_code,
                    "category" => "packet",
                    "product_id" => $request->product_id,
                ]);


                $stoProduct = StockTransferOrderProduct::findOrFail($request->order_product_id);
                $stoProduct->last_grn_quantity = $stoProduct->grn_quantity;
                $stoProduct->grn_quantity += 1;
                $stoProduct->save();

                // $grn = Grn::find($request->grn_id);
                // if ($grn) {
                //     $grn->grn_quantity += 1;
                //     $grn->save();
                // }

                Session::put('UN_SAVED_STO_GRN_ID', $request->grn_id);
                DB::commit();
                return $this->returnSuccess($GrnScanProduct);
            } else {

                $poProduct = PurchaseOrderProduct::find($request->order_product_id);
                $validator = Validator::make(
                    $request->all(),
                    [
                        'product_code' => 'required|startsWithProductName:' . $poProduct->product_id . ',|unique:grn_product_details,scanned_product_id',
                    ],
                    [
                        'product_code.startsWithProductName'    => 'Wrong Label Prefix',
                        'product_code.unique'    => 'Duplicate Entry',
                    ]
                );

                // Validation for Quantity and grn quantity
                $validator->after(function ($validator) use ($poProduct) {
                    if ($poProduct->grn_quantity >= $poProduct->quantity) {
                        $validator->errors()->add('product_qty', 'Entry exceeding the specified quantity is not allowed.');
                    }
                });

                if ($validator->fails()) {
                    return $this->returnError($validator->errors());
                }

                // $poGrnScanProduct = PurchaseOrderGrnScannedProduct::create([
                //     "grn_id" => $request->grn_id,
                //     "purchase_order_product_id" => $request->order_product_id,
                //     "scanned_product_id" => $request->product_code,
                //     "quantity" => 1,
                // ]);

                //Branch location from purchase order table
                $po = PurchaseOrder::find($poProduct->purchase_order_id);

                $GrnScanProduct = GrnProductDetail::create([
                    "grn_id" => $request->grn_id,
                    "location_id" => $po->delivery_location,
                    'order_id' => $po->id,
                    "op_product_id" => $request->order_product_id,
                    "scanned_product_id" => $request->product_code,
                    "category" => "packet",
                    "product_id" => $request->product_id,
                    // "quantity" => 1,
                ]);

                $purchaseOrderProducts = PurchaseOrderProduct::findOrFail($request->order_product_id);
                $purchaseOrderProducts->last_grn_quantity =  $purchaseOrderProducts->grn_quantity;
                $purchaseOrderProducts->grn_quantity += 1;
                $purchaseOrderProducts->save();

                // $grn = Grn::find($request->grn_id);
                // if ($grn) {
                //     $grn->grn_quantity += 1;
                //     $grn->save();
                // }
                DB::commit();
                Session::put('UN_SAVED_PO_GRN_ID', $request->grn_id);

                return $this->returnSuccess($GrnScanProduct);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function createGrnId(Request $request)
    {
        try {

            // $poGrnDetails = Grn::where('purchase_order_id', $request->purchase_order_id)
            //     ->WhereNotNull('grn_no')
            //     ->get();

            // if ( $poGrnDetails->isEmpty() ){



            // }else{
            //     foreach( $poGrnDetails as $poGrnDetail ){
            //         if( $poGrnDetail['quantity'] != $poGrnDetail['grn_quantity'] ){
            //             return $this->returnError(false, "Cannot create new GRN. Existing GRN is not completed.");
            //         }
            //     }
            // }
            if ($request->order_id || $request->order_id == 0) {
                $result = Grn::create([
                    "purchase_order_id" => ($request->order_id != 0) ? $request->order_id : null,
                    "type" => $request->type ?? null,
                ]);
            }
            return $this->returnSuccess($result);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function resetGrn(Request $request)
    {

        try {

            if ($request->type === "sto") {

                $stoProduct = StockTransferOrderProduct::find($request->order_product_id);
                $sto = StockTransferOrder::find($stoProduct->stock_transfer_order_id);

                $grnScanProducts = GrnProductDetail::where(['grn_id' => $request->grn_id, 'op_product_id' => $request->order_product_id])->get();
                if ($grnScanProducts) {
                    $products = [];
                    foreach ($grnScanProducts as $gp) {
                        if (!isset($products[$gp->product_id])) {
                            $products[$gp->product_id] = 0;
                        }
                        $products[$gp->product_id] += 1;

                        // tranfered label with location
                        if ($sto) {
                            GrnProductDetail::where('scanned_product_id', $gp->scanned_product_id)
                                ->where('location_id', $sto->stock_source_id)
                                ->where('is_pack_scanned', 1)
                                ->where('status', 'transfered')
                                ->update(['status' => 'transit']);
                        }

                        $gp->delete();
                    }
                    foreach ($products as $k => $v) {
                        $sto_product = StockTransferOrderProduct::where(['stock_transfer_order_id' => $sto->id, 'product_id' => $k])->first();
                        if ($sto_product) {
                            $sto_product->grn_quantity = abs($sto_product->grn_quantity - $v);
                            $sto_product->save();
                        }
                    }
                }

            } else {

                $grnScanProducts = GrnProductDetail::select('scanned_product_id')
                    ->where('grn_id', $request->grn_id)
                    ->where('op_product_id', $request->order_product_id)
                    ->get();
                if(!$grnScanProducts->isEmpty()){
                    $count = sizeof($grnScanProducts);
                    $orderProducts = PurchaseOrderProduct::findOrFail($request->order_product_id);
                    $orderProducts->grn_quantity = abs($orderProducts->grn_quantity - $count);
                    $orderProducts->save();
                }
                GrnProductDetail::select('scanned_product_id')
                    ->where('grn_id', $request->grn_id)
                    ->where('op_product_id', $request->order_product_id)
                    ->delete();
            }
            return $this->returnSuccess($grnScanProducts);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function saveGrn(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'grn_id' => 'required|numeric',
                'order_no' => 'required|numeric',
                'grn_location' => 'required|numeric',
                'sender_name' => 'required|numeric',
                'transporter_name' => 'required|numeric',
                'created_on' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->returnError($validator->errors());
            }

            // Save to GRN Table

            $grnDetails = Grn::find($request->grn_id);
            $grnDetails->grn_no = Grn::grnNoGenerator();
            $grnDetails->purchase_order_id = $request->order_no;
            $grnDetails->type = $request->type;
            $grnDetails->branch_location_id = $request->grn_location;
            $grnDetails->manufacturer_id = $request->sender_name;
            $grnDetails->transporter_id = $request->transporter_name;
            $grnDetails->created_on = $request->created_on;
            $grnDetails->created_by = auth()->user()->id;
            $grnDetails->save();



            if ($request->type == "sto") {
                // Create grn product details to grn relation table
                foreach ($request->product_details as $productDetail) {
                    $scanned_products_count = GrnProductDetail::where([['grn_id', $request->grn_id], ['op_product_id', $productDetail['id']]])->get()->count();

                    // Change the status on poproduct table quantity received fulfilled
                    if ($productDetail['quantity'] == $productDetail['grn_quantity']) {
                        $stoProduct = StockTransferOrderProduct::find($productDetail['id']);
                        $stoProduct->status = "Fulfilled";
                        $stoProduct->save();
                    }

                    // save the products to stocks table

                    $sto = StockTransferOrder::find($request->order_no);

                    // Add stock to destination location branch
                    $stockDestination = Stock::where('location_id', $sto->stock_destination_id)
                        ->where('product_id', $productDetail['product']['id'])
                        ->first();
                    if ($stockDestination) {
                        $stockData = Stock::find($stockDestination->id);
                        $stockData->stock += $scanned_products_count;
                        $stockData->save();
                    } else {
                        Stock::create([
                            'product_id' => $productDetail['product']['id'],
                            'location_id' => $sto->stock_destination_id,
                            'stock' => $productDetail['grn_quantity']
                        ]);
                    }

                    // Minus transit stock to source location branch
                    $stockSource = Stock::where('location_id', $sto->stock_source_id)
                        ->where('product_id', $productDetail['product']['id'])
                        ->first();
                    if ($stockSource) {
                        $stockData = Stock::find($stockSource->id);
                        $stockData->transit -= $scanned_products_count;
                        $stockData->save();
                    }
                    // save details to master grn relation table
                    $scanned_products_count = GrnProductDetail::where([['grn_id', $request->grn_id], ['op_product_id', $productDetail['id']]])->get()->count();

                    GrnRelation::create([
                        'grn_id' => $request->grn_id,
                        'sto_id' => $productDetail['stock_transfer_order_id'],
                        'sto_product_id' => $productDetail['id'],
                        'product_grn_quantity' => $scanned_products_count,
                    ]);
                }

                // If All label completed change the status of PO

                $stoProductData = StockTransferOrderProduct::where('stock_transfer_order_id', $request->order_no)->get();
                if (!empty($stoProductData)) {
                    $lastIndex = sizeof($stoProductData) - 1;
                    foreach ($stoProductData as $index => $stoProduct) {
                        if ($stoProduct->grn_quantity != $stoProduct->quantity) {
                            break;
                        }
                        if ($index === $lastIndex) {
                            $sto = StockTransferOrder::find($request->order_no);
                            $sto->status = "Fulfilled";
                            $sto->save();
                        }
                    }
                }
                Session::forget('UN_SAVED_STO_GRN_ID');
            } else {

                // Create grn product details to grn relation table
                foreach ($request->product_details as $productDetail) {
                    $scanned_products_count = GrnProductDetail::where([['grn_id', $request->grn_id], ['op_product_id', $productDetail['id']]])->get()->count();

                    $purProduct = PurchaseOrderProduct::find($productDetail['id']);
                    GrnProductDetail::where('grn_id', $request->grn_id)
                        ->where('location_id', $request->grn_location)
                        ->where('order_id', $purProduct->purchase_order_id)
                        ->where('op_product_id', $productDetail['id'])
                        ->where('type', 'po')
                        ->update([
                            'is_grn_saved' => 1,
                            'status' => 'Open'
                        ]);

                    // Change the status on poproduct table quantity received fulfilled
                    if ($productDetail['quantity'] == $productDetail['grn_quantity']) {
                        $poProduct = PurchaseOrderProduct::find($productDetail['id']);
                        $poProduct->status = "Fulfilled";
                        $poProduct->save();
                    }

                    // save the products to stocks table
                    $stocks = Stock::where('location_id', $request->grn_location)
                        ->where('product_id', $productDetail['product_id'])
                        ->first();
                    if ($stocks) {
                        $stockData = Stock::find($stocks->id);
                        // $stockData->stock += abs($productDetail['last_grn_quantity'] - $productDetail['grn_quantity']);
                        $stockData->stock += $scanned_products_count;
                        $stockData->save();
                    } else {
                        Stock::create([
                            'product_id' => $productDetail['product_id'],
                            'location_id' => $request->grn_location,
                            // 'stock' => $productDetail['grn_quantity']
                            'stock' => $scanned_products_count
                        ]);
                    }
                    // save details to master grn relation table


                    GrnRelation::create([
                        'grn_id' => $request->grn_id,
                        'purchase_order_id' => $productDetail['purchase_order_id'],
                        'purchase_order_product_id' => $productDetail['id'],
                        'product_grn_quantity' => $scanned_products_count,
                    ]);
                }

                // If All label completed change the status of PO

                $poProductData = PurchaseOrderProduct::where('purchase_order_id', $request->order_no)->get();
                if (!empty($poProductData)) {
                    $lastIndex = sizeof($poProductData) - 1;
                    foreach ($poProductData as $index => $poProduct) {
                        if ($poProduct->grn_quantity != $poProduct->quantity) {
                            break;
                        }
                        if ($index === $lastIndex) {
                            $po = PurchaseOrder::find($request->order_no);
                            $po->status = "Fulfilled";
                            $po->save();
                        }
                    }
                }
                Session::forget('UN_SAVED_PO_GRN_ID');
            }

            DB::commit();
            return $this->returnSuccess($grnDetails);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function orderProductList(Request $request)
    {
        try {
            if ($request->type && (isset($request->type))) {

                $grn_id = $request->input('grn_id');

                Grn::find($grn_id)->update(['purchase_order_id' => $request->po_id]);
                if ($request->type == 'po') {
                    $poGrnScanProducts = PurchaseOrder::with('purchase_order_products')->find($request->po_id);
                    if ($poGrnScanProducts && isset($poGrnScanProducts->purchase_order_products)) {
                        foreach ($poGrnScanProducts->purchase_order_products as &$product) {
                            $grn_quantity_currently_scanned_products = \App\Models\GrnProductDetail::where(['grn_id' => $grn_id, 'op_product_id' => $product['id'], 'location_id' => $poGrnScanProducts->delivery_location, 'status' => 'open'])->get()->count();
                            $product->grn_quantity_currently_scanned_products = isset($grn_quantity_currently_scanned_products) ? $grn_quantity_currently_scanned_products : 0;
                        }
                    }
                    return $this->returnSuccess($poGrnScanProducts);
                } elseif ($request->type == 'sto') {
                    $stoGrnScanProducts = StockTransferOrder::with('stock_transfer_order_products')->find($request->po_id);
                    if ($stoGrnScanProducts && isset($stoGrnScanProducts->stock_transfer_order_products)) {
                        foreach ($stoGrnScanProducts->stock_transfer_order_products as &$product) {
                            $grn_quantity_currently_scanned_products = \App\Models\GrnProductDetail::where(['grn_id' => $grn_id, 'op_product_id' => $product['id'], 'location_id' => $stoGrnScanProducts->stock_destination_id, 'status' => 'open'])->get()->count();
                            $product->grn_quantity_currently_scanned_products = isset($grn_quantity_currently_scanned_products) ? $grn_quantity_currently_scanned_products : 0;
                        }
                    }
                    return $this->returnSuccess($stoGrnScanProducts);
                }

            } else {
                $poGrnScanProducts = PurchaseOrderProduct::where('purchase_order_id', $request->po_id)->get();
                return $this->returnSuccess($poGrnScanProducts);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function grnSummary(Request $request)
    {
        if ($request->type === "sto") {
            $grnDetails = Grn::with([
                'branch_location:id,location',
                'sto_sender_name:id,location',
                'manufacturer:id,name',
                'transporter:id,name',
                'grn_relations'
            ])->find($request->grn_id);
            $grnProductDetails = $grnDetails->sto_products;
        } else {
            $grnDetails = Grn::with([
                'branch_location:id,location',
                'manufacturer:id,name',
                'transporter:id,name',
                'grn_relations'
            ])->find($request->grn_id);
            $grnProductDetails = $grnDetails->po_products;
        }
        return view('pages.stock_management.grn.summary', compact('grnDetails', 'grnProductDetails'));
    }

    public function pdfGenerator(Request $request)
    {


        $data = [];
        if ($request->type === "sto") {
            $purchase_order_grn = Grn::find($request->id);
            $data['purchase_order_no'] = $purchase_order_grn->stockTransferOrder->sto_no;
            $data['grn_no'] = $purchase_order_grn->grn_no;
            $data['manufacturer_name'] = $purchase_order_grn->sto_sender_name->location;
            $data['grn_location'] = $purchase_order_grn->branch_location->location;
            $data['order_date'] = $purchase_order_grn->created_on;
            $data['created_by'] = $purchase_order_grn->user->name;
            $data['transporter_name'] = $purchase_order_grn->transporter->name;
            if ($purchase_order_grn) {
                $purchase_order_grn_scanned_products = $purchase_order_grn->grn_relations;
                foreach ($purchase_order_grn_scanned_products as $purchase_order_grn_scanned_product) {
                    // $product = Product::find($purchase_order_grn_scanned_product->sto_id);
                    $data['products'][] = [
                        'name' => $purchase_order_grn_scanned_product->sto_product_name,
                        'quantity' => $purchase_order_grn_scanned_product->product_grn_quantity
                    ];
                }
            }
        } else {
            $purchase_order_grn = Grn::find($request->id);
            $data['purchase_order_no'] = $purchase_order_grn->purchaseOrder->purchase_order_no;
            $data['grn_no'] = $purchase_order_grn->grn_no;
            $data['manufacturer_name'] = $purchase_order_grn->manufacturer->name;
            $data['grn_location'] = $purchase_order_grn->branch_location->location;
            $data['order_date'] = $purchase_order_grn->created_on;
            $data['created_by'] = $purchase_order_grn->user->name;
            $data['transporter_name'] = $purchase_order_grn->transporter->name;
            if ($purchase_order_grn) {
                $purchase_order_grn_scanned_products = $purchase_order_grn->grn_relations;
                foreach ($purchase_order_grn_scanned_products as $purchase_order_grn_scanned_product) {
                    // $product = Product::find($purchase_order_grn_scanned_product->purchase_order_product_id);
                    $data['products'][] = [
                        'name' => $purchase_order_grn_scanned_product->product_name,
                        'quantity' => $purchase_order_grn_scanned_product->product_grn_quantity
                    ];
                }
            }
        }
        $fontPaths = [
            public_path('assets/font/NeueMontreal-Medium.otf'),
            public_path('assets/font/NeueMontreal-Regular.otf')
        ];
        $pdf = PDF::loadView('pdf.stock_management.purchase_order_grn', compact('data', 'fontPaths'));
        $response = response($pdf->output())->header('Content-Type', 'application/pdf');
        return $response;
    }
}
