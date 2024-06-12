<?php

namespace App\Http\Controllers;

use App\Exports\PurchaseOrderExport;
use App\Models\BranchLocation;
use App\Models\Grn;
use App\Models\GrnProductDetail;
use App\Models\GrnRelation;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Auth;
use \PDF;
use Maatwebsite\Excel\Facades\Excel;


class PurchaseOrderController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;

    public function purchaseOrderList(Request $request)
    {
        $export = $request->input('export');
        $search = $request->input('search');
        $search_column = $request->input('search_column');
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $sort = $request->input('sort') ?? 'created_at|desc';

        $delivery_location = $request->input('delivery_location');
        $manufacturer = $request->input('manufacturer');
        $request_status = $request->input('status');

        if ($sort) $sort = explode('|', $sort);

        $data = DB::table('purchase_orders');

        if ($request_status)
            $data->whereIn('purchase_orders.status', $request_status);

        $data->join('branch_locations', 'branch_locations.id', '=', 'purchase_orders.delivery_location');

        if ($delivery_location)
            $data->whereIn('branch_locations.location', $delivery_location);
            $data->join('manufacturers', 'manufacturers.id', '=', 'purchase_orders.manufacturer_name');

        if ($manufacturer) $data->whereIn('manufacturers.name', $manufacturer);

        $data = $data->select('purchase_orders.id', 'purchase_orders.order_date', 'purchase_orders.purchase_order_no', 'branch_locations.location', 'manufacturers.name', 'purchase_orders.status', 'purchase_orders.created_at')
            ->where(function ($query) use ($search) {
                $query->orWhere('purchase_orders.order_date', 'like', "%$search%");
                $query->orWhere('purchase_orders.purchase_order_no', 'like', "%$search%");
                $query->orWhere('manufacturers.name', 'like', "%$search%");
                $query->orWhere('branch_locations.location', 'like', "%$search%");
            })
            ->orderBy($sort[0], $sort[1]);

        if($export) {
            $data = $data->get();
            if($data->isEmpty()) return $this->returnError('No data found for export');

            return Excel::download(new PurchaseOrderExport($data->toArray()), 'purchase_order.xlsx');
        } else $data = $data->paginate($per_page, ['*'], 'page', $page);

        $total = $data->total();

        $check_value_exists_table = PurchaseOrder::get()->isEmpty();

        if ($request->ajax()) {
            return [
                'table' => view('pages.stock_management.purchase_order.list_table', compact('data', 'total', 'check_value_exists_table'))->render(),
                'pagination' => view('components.pagination', compact('data'))->render(),
                'total' => $total,
            ];
        }

        $manufacturers = Manufacturer::select('name')->whereNotNull('name')->where('status', 1)->orderBy('name', 'asc')->get()->pluck('name');
        $delivery_locations = BranchLocation::select('location')->whereNotNull('location')->where('status', 1)->orderBy('location', 'asc')->get()->pluck('location');

        return view('pages.stock_management.purchase_order.index', compact('data', 'total', 'manufacturers', 'delivery_locations', 'check_value_exists_table'));
    }

    public function purchaseOrderCreate(Request $request)
    {
        $manufactures =  Manufacturer::orderBy('name', 'asc')->get();
        $deliveryLocations = BranchLocation::whereNotNull('location')->where('status', 1)->orderBy('location', 'asc')->get();
        $products = Product::all();
        return view('pages.stock_management.purchase_order.add_edit', compact('manufactures', 'deliveryLocations', 'products'));

    }


    public function createValidate( Request $request ){
        try {
            $validator = Validator::make($request->all(), [
                'manufacturer' => 'required|string|max:64',
                'location' => 'required|string|max:64',
                'purchase_no' => 'required|string|max:24',
                'order_date' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->returnError($validator->errors());
            }
            $createPoData =  $request->toArray();
            session([ 'createPoData' => $createPoData ]);
            return response()->json(['redirect' => route('purchase-orders.summary'), 'createPoData' => $createPoData]);

        } catch (Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function productValidate( Request $request ){

        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->returnError($validator->errors());
            }
            return $this->returnSuccess(true);
        } catch (Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'manufacturer' => 'required|string|max:64',
                'location' => 'required|string|max:64',
                'purchase_no' => 'required|string|max:24|unique:purchase_orders,purchase_order_no',
                'order_date' => 'required',
                'product_details' => 'required|array',
                'product_details.*.product_id' => 'required',
                'product_details.*.quantity' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->returnError($validator->errors());
            }

            $purchaseOrder = PurchaseOrder::create([
                'manufacturer_name' => $request->manufacturer,
                'delivery_location' => $request->location,
                'purchase_order_no' => $request->purchase_no,
                'generated_by' => auth()->user()->id,
                'order_date' => $request->order_date,
                'status' => "Open"
            ]);
            foreach ($request->product_details as $product_detail) {
                PurchaseOrderProduct::create([
                    "product_id" => $product_detail['product_id'],
                    "purchase_order_id" => $purchaseOrder->id,
                    'po_no' => $request->purchase_no,
                    "quantity" => $product_detail['quantity'],
                ]);
            }

            return $this->returnSuccess($purchaseOrder, "Purchase order saved successfully");
        } catch (Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    // public function createSummaryPO(Request $request)
    // {

    //     $createPoData = session( 'createPoData' );
    //     return view('pages.stock_management.purchase_order.create_po_summary', compact('createPoData'));
    // }

    private function purchaseOrderNoGenerator()
    {

        $maxNO = DB::select("select max(cast(substring(purchase_order_no,4) as signed)) as max_purchase_no from as_purchase_orders");
        if ($maxNO[0]->max_purchase_no) {
            $filterNum = $maxNO[0]->max_purchase_no;
            $filterNum++;
            $filterNumLength = strlen($filterNum);
            $clearNumber = str_pad($filterNum, $filterNumLength + 2, '0', STR_PAD_LEFT);
            $pur_order_no = "PO" . $clearNumber;
        } else {
            $filterNum = 1;
            $pur_order_no = "PO00" . $filterNum;
        }
        return $pur_order_no;
    }

    public function summaryPo( $poId ){

        // Delete non saved grn label Entry
        $po = PurchaseOrder::find($poId);
        $grnIds = Grn::select('id')
                    ->where('purchase_order_id', $poId)
                    ->where('grn_no', null)
                    ->pluck('id')->toArray();

        foreach ($grnIds as $grnId) {

            $grnOpProductIds = GrnProductDetail::select('op_product_id')
                                                    ->where('grn_id', $grnId)
                                                    ->where('type', 'po')
                                                    ->pluck('op_product_id')->toArray();

            foreach ($grnOpProductIds as $grnOpProductId){

                $grnRelationData = GrnRelation::where('purchase_order_product_id', $grnOpProductId)
                            ->latest()
                            ->first();
                // Rollback the data of purchase order product
                $poProduct = PurchaseOrderProduct::find($grnOpProductId);
                ($grnRelationData) ? $poProduct->grn_quantity = $grnRelationData->product_grn_quantity : $poProduct->grn_quantity = 0;
                $poProduct->save();

                GrnProductDetail::where('op_product_id', $grnOpProductId)
                                ->where('grn_id', $grnId)
                                ->where('type', 'po')
                                ->where('is_grn_saved', 0)
                                ->where('status', null)
                                ->delete();
            }
        }
        // Delete the unwanted Grn id's
        GRN::whereIn('id', $grnIds)->delete();

        // Get and set purchase order details on summary page
        $purchase_order = purchaseOrder::with(['purchase_order_products', 'purchase_order_products.grn_relation'])->find($poId);
        return view('pages.stock_management.purchase_order.summary', compact('purchase_order'));
    }

    public function print($id = null)
    {
        $purchase_order = PurchaseOrder::find($id);
        if (!$purchase_order) return $this->returnError('Purchase order not found');

        $purchase_order_products = $purchase_order->purchaseOrderProducts;
        if (!$purchase_order_products) return $this->returnError('Purchase Order Products not found');

        return view('pages.stock_management.purchase_order.purchase_order_print', compact('purchase_order', 'purchase_order_products'));
    }

    public function pdfGenerator($id){
        $data = [];
        $purchase_order = PurchaseOrder::find($id);
        $data['purchase_order_no'] = $purchase_order->purchase_order_no;
        $data['manufacturer_name'] = $purchase_order->manufacturer->name;
        $data['order_date'] = $purchase_order->order_date;
        $data['created_by'] = $purchase_order->user->name;
        $data['products'] = [];
        if ($purchase_order){
            $purchase_order_products = $purchase_order->purchaseOrderProducts;
            foreach($purchase_order_products as $purchase_order_product){
                $data['products'][] = ['name' => $purchase_order_product->products->name,
                'qty' => $purchase_order_product->quantity];
            }
        }
        $fontPaths = [
            public_path('assets/font/NeueMontreal-Medium.otf'),
            public_path('assets/font/NeueMontreal-Regular.otf')
        ];
        $pdf = PDF::loadView('pdf.stock_management.purchase_order', compact('data' , 'fontPaths'));
        $response = response($pdf->output())->header('Content-Type', 'application/pdf');
        return $response;
    }
}
