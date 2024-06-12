<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use App\Models\Product;
use App\Models\DamageType;
use App\Models\LabelDamage;
use App\Models\LabelDamageProducts;
use App\Models\LabelDamageDetails;
use App\Models\LabelProductDetail;
use App\Models\GrnProductDetail;
use App\Models\BranchLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Session;
use Auth;
use App\Exports\LabelDamageExport;
use Maatwebsite\Excel\Facades\Excel;

class LabelDamageController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 4;

    // Re-usable Functions
    public function FetchProducts($reference_id){
        $products = LabelDamage::where("reference_id", $reference_id)->distinct()->get();
        $products_list = [];
        if($products){
            foreach ($products as $d => $pro) {
                $dmProducts = $pro->damageProducts;
                foreach ($dmProducts as $dm => $dmPro) {
                    $products_list[] = array(
                        'sr_no' => ++$dm,
                        'damage_id' => $dmPro->id,
                        'product' => $this->ProductIdToName($dmPro->product_id),
                        'qty' => $dmPro->qty
                    );
                }
            }
        }

        return $products_list;
    }
    public function ProductIdToName($product_id){
        $product = Product::where("id", $product_id)->first();
        if($product){
            return $product->name;
        }

        return "";
    }
    public function damageIdToName($damage_id){
        $damage_type = DamageType::where("id", $damage_id)->first();
        if($damage_type){
            return $damage_type->name;
        }

        return "";
    }
    public function GenerateReferenceID(){
        $make_reference = DB::table('label_damages')->latest()->first();
        $reference_id = "";
        if($make_reference){
            $numbersIn = substr($make_reference->reference_id, -6);
            $numbersIn++;
            $numbersInLength = strlen($numbersIn);
            $final = str_pad($numbersIn, $numbersInLength + 5, '0', STR_PAD_LEFT);

            $reference_id = "DIL" . $final;
        }
        else{
            $reference_id = "DIL000001";
        }

        return Session::has('REFERENCE_ID') ? Session::get('REFERENCE_ID') : $reference_id;
    }
    // Pages
    public function labelDamagedList(Request $request)
    {
        //Session::forget('REFERENCE_ID');
        $sortup = [
            'field' => "",
            'value' => "",
        ];
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);

        $damage = DB::table('label_damages as ld');
        $damage->leftJoin('branch_locations as bl', 'ld.location_id', '=', 'bl.id');
        $damage->leftJoin('users as us', 'ld.created_by', '=', 'us.id');
        $damage->where('ld.status', '=', 'completed');
        $damage->select('ld.reference_id', 'bl.location', 'us.name', 'ld.date', 'ld.id');
        if ($request->ajax()) {
            $search_val = $request->search;
            $sort = $request->sort_field;
            $direction = $request->direction;

            $sortup["field"]= $sort;

            if($sort){

                $change_icon = $direction == "asc" ? "desc" : "asc";

                if($sort =="reference"){
                    $damage->orderBy('ld.reference_id', $change_icon);
                    $sortup["value"]= $direction;
                }
                else if($sort =="date"){
                    $damage->orderBy('ld.date', $change_icon);
                    $sortup["value"]= $direction;
                }
                else if($sort =="added_by"){
                    $damage->orderBy('ld.created_by', $change_icon);
                    $sortup["value"]= $direction;
                }
                else if($sort =="location"){
                    $damage->orderBy('bl.location', $change_icon);
                    $sortup["value"]= $direction;
                }
            }

            if($search_val!=''){
                $damage->where(function ($query) use ($search_val) {
                    $query->orWhere('ld.reference_id', 'like', "%$search_val%")->orWhere('us.name', 'like', "%$search_val%")->orWhere('bl.location', 'like', "%$search_val%");
                });
            }
        }
        $all_labels = $damage->paginate($per_page, ['*'], 'page', $page);

        $all_label_list = [];
        if(sizeof($all_labels) > 0){
            foreach($all_labels as $all => $row){
                $little_count = 0;
                $damage_products = LabelDamageProducts::where("damage_id", $row->id)->get();
                if(sizeof($damage_products) > 0){
                    foreach($damage_products as $ma => $product){
                        $little_count = $little_count + $product->qty;
                    }
                }
                $all_label_list[]=[
                    "ref_no" => $row->reference_id,
                    "date" => Carbon::parse($row->date)->format('d M, Y'),
                    "added_by" => $row->name,
                    "location" => $row->location,
                    "total_damaged_label" => $little_count,
                ];
            }
        }

        $paginate = $all_labels->toArray() ?? [];

        if ($request->ajax()) {
            return [
                'pagination' => view('pages.label.damaged.tables.table_paginate', ["paginate"=>$paginate])->render(),
                'table' => view('pages.label.damaged.tables.index_table', ["labels"=>$all_label_list, "sortup"=>$sortup])->render(),
            ];
        }

        return view('pages.label.damaged.index', [
            "labels" => $all_label_list,
            "paginate"=> $paginate,
            "sortup"=>$sortup
        ]);
    }
    public function labelDamagedCreate(Request $request)
    {
        $REFERENCE_ID = $this->GenerateReferenceID();
        Session::put('REFERENCE_ID', $REFERENCE_ID);
        $all_locations = BranchLocation::where("status", true)->orderBy("location")->get();
        $info = LabelDamage::where("reference_id", $REFERENCE_ID)->first();

        return view('pages.label.damaged.create', [
            "reference_id"=> $REFERENCE_ID,
            "locations"=> $all_locations,
            "selected_loc"=> $info ? $info->location_id : "",
            "products"=> $REFERENCE_ID ? $this->FetchProducts($REFERENCE_ID) : [],
        ]);
    }
    public function labelDamagedSummary(Request $request, $product_id)
    {
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);

        $response = [
            'info' => "",
            'product_id' => "",
            'damage_id' => "",
            'product_type' => "",
            'label_list' => [],
            'damage_types' => [],
            'damage_details' => []
        ];

        $damage_product = LabelDamageProducts::where("id", $product_id)->first();
        if($damage_product){
            $info = LabelDamage::where("id", $damage_product->damage_id)->first();
            $ldetails = LabelDamageDetails::where([["damage_id", $info->id],["product_id", $damage_product->product_id]])->paginate($per_page, ['*'], 'page', $page);
            $paginate = $ldetails->toArray() ?? [];
            $response["info"] = $info;
            $response["product_type"] = $this->ProductIdToName($damage_product->product_id);
            $response["damage_id"] = $damage_product->damage_id;
            $response["product_id"] = $damage_product->product_id;
            $response["product_id_url"] = $product_id;
            $response["damage_types"] = DamageType::where("status", true)->get();
            $response["label_list"] = GrnProductDetail::where("location_id", $info->location_id)->where("product_id", $damage_product->product_id)->whereIn("status", ["issued", "open"])->get();
            $response["damage_details"] = $ldetails;
            $response["paginate"] = $paginate;

             if ($request->ajax()) {
                return [
                    'pagination' => view('pages.label.damaged.tables.table_paginate', ["paginate"=>$paginate])->render(),
                    'table' => view('pages.label.damaged.tables.summary_table', $response)->render(),
                ];
            }
        }

        return view('pages.label.damaged.summary', $response);
    }
    // Ajax Submit
    // public function fetchSummary(Request $request, $product_id)
    // {
    //     $return = [
    //         'info' => "",
    //         'product_id'=> "",
    //         'damage_id'=> "",
    //         'product_type' => "",
    //         'label_list' => [],
    //         'damage_types' => [],
    //         'damage_details' => [],
    //     ];
    //     $damage_product = LabelDamageProducts::where("id", $product_id)->first();
    //     if($damage_product){
    //         $info = LabelDamage::where("id", $damage_product->damage_id)->first();
    //         $return["info"] = $info;
    //         $return["product_type"] = $this->ProductIdToName($damage_product->product_id);
    //         $return["damage_id"] = $damage_product->damage_id;
    //         $return["product_id"] = $damage_product->product_id;
    //         $return["product_id_url"] = $product_id;
    //         $return["damage_types"] = DamageType::where("status", true)->get();
    //         $return["label_list"] = GrnProductDetail::where("location_id", $info->location_id)->where("product_id", $damage_product->product_id)->whereIn("status", ["issued", "open"])->get();
    //         $return["damage_details"] = LabelDamageDetails::where([["damage_id", $info->id],["product_id", $damage_product->product_id]])->get();
    //     }

    //     return view('pages.label.damaged.tables.summary_table', $return);
    // }
    public function labelDamagedSave(Request $request)
    {
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'reference_id' => 'required',
                'location_id' => 'required',
                'product_type' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->returnError($validator->errors());
            }

            $REFERENCE_ID = Session::get('REFERENCE_ID');
            $all_label = LabelDamage::where("reference_id", $REFERENCE_ID)->get();
            if(sizeof($all_label)>=1){
                $one = $all_label[0];
                $damage_product = new LabelDamageProducts();
                $damage_product->damage_id = $one->id;
                $damage_product->product_id = $request->product_type;
                $damage_product->qty = 0;
                $damage_product->save();
            }
            else{
                $damage_label = new LabelDamage();
                $damage_label->reference_id = $request->reference_id;
                $damage_label->location_id = $request->location_id;
                $damage_label->created_by = auth()->user()->id;
                $damage_label->updated_by = auth()->user()->id;
                $saved = $damage_label->save();
                if($saved){
                    $damage_product = new LabelDamageProducts();
                    $damage_product->damage_id = $damage_label->id;
                    $damage_product->product_id = $request->product_type;
                    $damage_product->qty = 0;
                    $damage_product->save();
                }
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage());
        }

        DB::commit();
        return $this->returnSuccess("success", "Label saved successfully");
    }
    public function updateSummary(Request $request)
    {
        $res = [
            'status' => 'failure',
            'message' => 'Unable to update the damage summary!',
        ];

        DB::beginTransaction();
        try{
            $data = $request->only('damaged_id', 'product_id', 'code', 'label_id', 'damage_type', 'product_id_url');
            $rules = [
                'code' => 'required|unique:label_damage_product_details',
                'label_id' => 'required',
                'damage_type' => 'required'
            ];
            $msg = [
                'code.required' => 'The :attribute field is required.',
                'code.unique' => 'This Envelope ID already exists!',
            ];
            $validate = Validator::make($data, $rules, $msg);
            if($validate->fails()) {
                $all_errors = $validate->errors()->toArray();
                $errors = array_shift($all_errors);
                $res["status"] = "validation_error";
                $res["message"] = $errors[0];
                return response()->json($res);
            }

            $damage_details = new LabelDamageDetails();
            $damage_details->damage_id = $request->damaged_id;
            $damage_details->product_id = $request->product_id;
            $damage_details->code = $request->code;
            $damage_details->envelope_label_code = $request->label_id;
            $damage_details->damage_type_id = $request->damage_type;
            $damage_details->damage_type_txt = $this->damageIdToName($request->damage_type);
            $damage_details = $damage_details->save();
            if($damage_details){
                $damage_product = LabelDamageProducts::where('id', $request->product_id_url)->first();
                if($damage_product){
                    $damage_product->qty = $damage_product->qty + 1;
                    $damage_product->save();
                }
            }

            $res['status'] = 'success';
            $res['message'] = 'Summary saved successfully';
        }
        catch (Exception $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage());
        }

        DB::commit();
        return response()->json($res);
    }
    // Actions
    public function updateDamagedLabels(Request $request)
    {
        $res = [
            'status' => 'failure',
            'msg' => 'Unable to update the damage summary!',
        ];
        DB::beginTransaction();
        try{
            $REFERENCE_ID = Session::get('REFERENCE_ID');
            $label = LabelDamage::where("reference_id", $REFERENCE_ID)->first();
            if($label){
                $products = LabelDamageDetails::select('code')->where('damage_id', $label->id)->get();
                if($products){
                    $scaned_products = [];
                    foreach ($products as $d => $pro) {
                        $scaned_products[]=$pro->code;
                    }

                    $all_scaned_products = GrnProductDetail::where("location_id", $label->location_id)->whereIn("scanned_product_id", $scaned_products)->get();
                    if(sizeof($all_scaned_products) > 0){
                        foreach ($all_scaned_products as $all_scaned => $one) {
                            $fg = "damaged";

                            if($one->status == "issued"){
                                $used_product = LabelProductDetail::where("code", $one->scanned_product_id)->where("status", "issued")->first();
                                if($used_product){
                                    $used_product->damaged=now();
                                    $used_product->status="damaged";
                                    $used_product->save();
                                }

                                $fg = "agent-damaged";
                                //$one->status= "damaged";
                            }

                            $one->status= $fg;
                            $one->save();
                        }
                    }
                }

                $label->status="completed";
                $label->save();
            }

            $res['status'] = 'success';
            $res['msg'] = 'Summary saved successfully';
        }
        catch (Exception $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage());
        }

        Session::forget('REFERENCE_ID');
        DB::commit();
        return response()->json($res);
    }

    // public function updateProduct(Request $request)
    // {

    //     return view('pages.label.damaged.summary');
    // }
    public function labelPopup(Request $request)
    {
        $return = [
            'added_products' => [],
            'products' => [],
        ];

        if(Session::has('REFERENCE_ID')){
            $REFERENCE_ID = Session::get('REFERENCE_ID');
            $damage_label = LabelDamage::where("reference_id", $REFERENCE_ID)->first();
            if($damage_label){
                $all = LabelDamageProducts::where("damage_id", $damage_label->id)->get();
                $product_ids = [];
                foreach ($all as $a => $row) {
                    $product_ids[]=$row->product_id;
                }
                $return["added_products"] = $product_ids;
            }
        }

        $return["products"] = Product::all();
        return view('pages.label.damaged.modals.label', $return);
    }
    public function getProducts(Request $request, $damaged_id)
    {
        return view('pages.label.damaged.tables.product_table', [
            "products"=> $this->FetchProducts($damaged_id)
        ]);
    }
    public function deleteSummary(Request $request, $summary_id)
    {
        $return = [
            'status' => "failure",
            'message' => "",
        ];

        try {
            $label_damage = LabelDamageDetails::find($summary_id);
            if($label_damage){
                $damage_product = LabelDamageProducts::where('damage_id', $label_damage->damage_id)->where('product_id', $label_damage->product_id)->first();
                if($damage_product){
                    $damage_product->qty = $damage_product->qty == 0 ? 0 : $damage_product->qty - 1;
                    $damage_product->save();

                    $label_damage->delete();
                }
                $return["status"]="deleted";
                $return["message"]="Scanned label has been deleted!";
            }
        } catch (Exception $e) {
            $return["status"]="error";
            $return["message"]="Somthing went wrong!";
            $return["error"]=$e->getMessage();
        }

        return response()->json($return);
    }
    public function deleteAllSummary(Request $request)
    {
        $return = [
            'status' => "failure",
            'message' => "",
        ];

        try {
            $REFERENCE_ID = Session::get('REFERENCE_ID');
            $label = LabelDamage::where("reference_id", $REFERENCE_ID)->first();
            if($label){
                $damage_product = LabelDamageProducts::where('damage_id', $label->id)->first();
                if($damage_product){
                    $damage_product->qty = 0;
                    $damage_product->save();

                    $damage = LabelDamageDetails::where("damage_id", $label->id)->where("product_id", $damage_product->product_id)->delete();
                    if($damage){
                        $return["status"]="deleted";
                        $return["message"]="All Scanned label has been deleted!";
                    }
                    else{
                        $return["status"]="notfound";
                        $return["message"]="There is no scanned label available!";
                    }
                }
                else{
                    $return["status"]="notfound";
                    $return["message"]="There is no scanned label available!";
                }
            }
            else{
                $return["status"]="notfound";
                $return["message"]="There is no scanned label available!";
            }
        } catch (Exception $e) {
            $return["status"]="error";
            $return["message"]="Somthing went wrong!";
            $return["e"]=$e->getMessage();
        }

        return response()->json($return);
    }
    public function clearAllProducts(Request $request, $location_id){
        $return = [
            'status' => "failure",
            'message' => "",
        ];

        if(Session::has('REFERENCE_ID')){
            $REFERENCE_ID = Session::get('REFERENCE_ID');
            $damage_label = LabelDamage::where("reference_id", $REFERENCE_ID)->first();
            if($damage_label){
                $damage_label->location_id=$location_id;
                $damage_label->save();

                $deleted = LabelDamageProducts::where("damage_id", $damage_label->id)->delete();
                if($deleted){
                    $return = [
                        'status' => "deleted",
                        'message' => "all cleared",
                    ];
                }
            }
        }
        else{
            $return = [
                'status' => "notfound",
                'message' => "There is no scanned label available!",
            ];
        }

        return response()->json($return);
    }

    public function export(Request $request)
    {
        try {
            $search = $request->input('search');
            $damage = DB::table('label_damages as ld')
                ->leftJoin('branch_locations as bl', 'ld.location_id', '=', 'bl.id')
                ->leftJoin('users as us', 'ld.created_by', '=', 'us.id')
                ->where('ld.status', '=', 'completed')
                ->select('ld.reference_id', 'bl.location', 'us.name', 'ld.date', 'ld.id');
                if($search!=''){
                    $damage->where(function ($query) use ($search) {
                        $query->orWhere('ld.reference_id', 'like', "%$search%")->orWhere('us.name', 'like', "%$search%")->orWhere('bl.location', 'like', "%$search%");
                    });
                }
            $all_labels = $damage->get(); 
            $query = [];
            if ($all_labels->count() > 0) { 
                foreach ($all_labels as $row) {
                    $little_count = 0;
                    $damage_products = LabelDamageProducts::where("damage_id", $row->id)->get();
                    if ($damage_products->count() > 0) {
                        foreach ($damage_products as $product) {
                            $little_count += $product->qty;
                        }
                    }
                    $query[] = [
                        "ref_no" => $row->reference_id,
                        "date" => Carbon::parse($row->date)->format('d M, Y'),
                        "added_by" => $row->name,
                        "location" => $row->location,
                        "total_damaged_label" => $little_count,
                    ];
                }
            }
            $damaged_data = collect($query); 
            return Excel::download(new LabelDamageExport($damaged_data), 'LabelDamageExport.xlsx');
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }
    
}
