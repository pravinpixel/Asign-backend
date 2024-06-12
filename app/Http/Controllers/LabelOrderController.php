<?php

namespace App\Http\Controllers;

use App\Models\BranchLocation;
use App\Models\LabelOrder;
use App\Models\LabelOrderDetail;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LabelOrderController extends Controller
{
    public function index(Request $request)
    {

        $page = [
            'name' => 'Purchase Orders',
            'link' => 'label-orders',
            'add' => 'Create PO',
        ];
        
        $export = $request->input('export');
        $search = $request->input('search');
        $search_column = $request->input('search_column');
        $pages = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $sort = $request->input('sort') ?? 'created_at|desc';

        $delivery_location = $request->input('delivery_location');
        $manufacturer = $request->input('manufacturer');
        $request_status = $request->input('status');

        if ($sort) $sort = explode('|', $sort);

        $data = DB::table('label_orders as lo');

        if ($request_status)
            $data->whereIn('lo.status', $request_status);

        $data->join('branch_locations', 'branch_locations.id', '=', 'lo.delivery_location_id');

        if ($delivery_location)
            $data->whereIn('branch_locations.location', $delivery_location);
            $data->join('manufacturers', 'manufacturers.id', '=', 'lo.manufacturer_id');

        if ($manufacturer) $data->whereIn('manufacturers.name', $manufacturer);

        $data = $data->select('lo.id', 'lo.order_date', 'lo.order_no', 'branch_locations.location', 'manufacturers.name', 'lo.status', 'lo.created_at')
            ->where(function ($query) use ($search) {
                $query->orWhere('lo.order_date', 'like', "%$search%");
                $query->orWhere('lo.order_no', 'like', "%$search%");
                $query->orWhere('manufacturers.name', 'like', "%$search%");
                $query->orWhere('branch_locations.location', 'like', "%$search%");
            })
            ->orderBy($sort[0], $sort[1]);

        // if($export) {
        //     $data = $data->get();
        //     if($data->isEmpty()) return $this->returnError('No data found for export');

        //     return Excel::download(new PurchaseOrderExport($data->toArray()), 'purchase_order.xlsx');
        // } else 
        $data = $data->paginate($per_page, ['*'], 'page', $pages);

        $total = $data->total();

        $check_value_exists_table = LabelOrder::get()->isEmpty();

        if ($request->ajax()) {
            return [
                'table' => view('pages.label.order.tables.index_table', compact('data', 'total', 'check_value_exists_table'))->render(),
                'pagination' => view('components.pagination', compact('data'))->render(),
                'total' => $total,
            ];
        }

        $manufacturers = Manufacturer::select('name')->whereNotNull('name')->where('status', 1)->orderBy('name', 'asc')->get()->pluck('name');
        $delivery_locations = BranchLocation::select('location')->whereNotNull('location')->where('status', 1)->orderBy('location', 'asc')->get()->pluck('location');

        return view('pages.label.order.index', compact('data', 'total', 'manufacturers', 'delivery_locations', 'check_value_exists_table', 'page'));
    }

    public function show(Request $request)
    {
        $id = $request->id;

        // $result = $this->getLabelMaster();
        // $agents = $result['agents'];
        // $products = $result['products'];

        $label = null;
        $disabled = true;
        $products = Product::where('status', 1)->orderBy('name')->get(['id', 'name'])->toArray();
        $manufactures =  Manufacturer::orderBy('name', 'asc')->get();
        $deliveryLocations = BranchLocation::whereNotNull('location')->where('status', 1)->orderBy('location', 'asc')->get();
        // if (is_numeric($id)) {
        //     $label = Label::find($id);
        //     if (!$label)
        //         return redirect()->route('label-request.index')->with('error', 'Invalid label id');
        //     $request_id = $label->request_id;
        //     if ($label->status != Label::STATUS['requested']['id'])
        //         $disabled = true;
        //     if ($label->productDetails()->count() > 0)
        //         $disabled = true;
        // } else {
        //     $request_id = UtilsHelper::getMaxRequestNo('as_labels');
        // }

        // return view('pages.label.action', compact('agents', 'products', 'label', 'request_id', 'disabled'));
        return view('pages.label.order.action', compact('products', 'disabled', 'manufactures', 'deliveryLocations'));

    }

    public function showSummary($id){

        // Delete non saved grn label Entry
        $purchase_order = LabelOrder::with(['manufacturer:id,name', 'location:id,location', 'label_order_details.products', 'user:id,name'])->find($id);
        // $grnIds = Grn::select('id')
        //             ->where('purchase_order_id', $poId)
        //             ->where('grn_no', null)
        //             ->pluck('id')->toArray();

        // foreach ($grnIds as $grnId) {

        //     $grnOpProductIds = GrnProductDetail::select('op_product_id')
        //                                             ->where('grn_id', $grnId)
        //                                             ->where('type', 'po')
        //                                             ->pluck('op_product_id')->toArray();

        //     foreach ($grnOpProductIds as $grnOpProductId){

        //         $grnRelationData = GrnRelation::where('purchase_order_product_id', $grnOpProductId)
        //                     ->latest()
        //                     ->first();
        //         // Rollback the data of purchase order product
        //         $poProduct = PurchaseOrderProduct::find($grnOpProductId);
        //         ($grnRelationData) ? $poProduct->grn_quantity = $grnRelationData->product_grn_quantity : $poProduct->grn_quantity = 0;
        //         $poProduct->save();

        //         GrnProductDetail::where('op_product_id', $grnOpProductId)
        //                         ->where('grn_id', $grnId)
        //                         ->where('type', 'po')
        //                         ->where('is_grn_saved', 0)
        //                         ->where('status', null)
        //                         ->delete();
        //     }
        // }
        // // Delete the unwanted Grn id's
        // GRN::whereIn('id', $grnIds)->delete();

        // // Get and set purchase order details on summary page
        // $purchase_order = purchaseOrder::with(['purchase_order_products', 'purchase_order_products.grn_relation'])->find($poId);
        return view('pages.label.order.summary', compact('purchase_order'));
    }

    public function save(Request $request)
    {
        
        try {

            $validator = $request->validate([
                'manufacturer' => 'required|string|max:64',
                'location' => 'required|string|max:64',
                'purchase_no' => 'required|string|max:24|unique:label_orders,order_no',
                'order_date' => 'required',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.qty' => 'required|integer|min:1',
            ]);


            // $agent_id = $validator['agent_id'];
            $route_name = $request->route()->getName();

            $message = "Purchase Order has been created successfully";

            DB::beginTransaction();


            // $agent = User::where(['id' => $agent_id, 'role_id' => UtilsHelper::FIELD_AGENT])
            //     ->first();
            // if (!$agent)
            //     return $this->returnError('Invalid agent id');
            // if ($agent->branch_office_id == null)
            //     return $this->returnError('Agent branch office is not set');
        
            if ($route_name == 'label-orders.save') {

                $label = LabelOrder::create([
                    'order_no' => $request->purchase_no,
                    'manufacturer_id' => $request->manufacturer,
                    'delivery_location_id' => $request->location,
                    'order_date' => $request->order_date,
                    'created_by' => auth()->user()->id
                ]);


                foreach ($request->items as $product_detail) {
                    LabelOrderDetail::create([
                        "label_order_id" => $label->id,
                        "product_id" => $product_detail['product_id'],
                        "qty" => $product_detail['qty'],
                    ]);
                }
                // $label = Label::where('agent_id', $agent_id)->where('status', '!=', Label::STATUS['closed']['id'])->first();
                // if ($label)
                //     return $this->returnError('invalid-status', 'Label is already requested');

                // $no = UtilsHelper::getMaxRequestNo('as_labels');
                // if ($no != $validator['request_id'])
                //     return $this->returnError('invalid-request-no', [
                //         'request_id' => $no,
                //         'msg' => 'Your request id is already used by another user New Id: ' . $no
                //     ]);

                // $label = new Label();
                // $label->request_id = $no;
                // $label->request_date = now();
            }


            //  else {
            //     $label = Label::find($request->id);
            //     if ($label->status != Label::STATUS['requested']['id'])
            //         return $this->returnError('invalid-status', 'Label is already processed');
            //     if ($label->location_id != $agent->branch_office_id)
            //         return $this->returnError('invalid-branch-office', 'Agent branch office is not matched');

            //     $message = "Label updated successfully";
            // }


          
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess($label->id, $message);
    }
}
