<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use App\Models\BranchLocation;
use App\Models\StockByAgent;
use App\Models\Label;
use App\Models\LabelProduct;
use App\Models\LabelProductDetail;
use Illuminate\Support\Facades\Route;
class LabelStockController extends Controller
{

    public function index(Request $request)
    {
        $data = Product::with('stock')->get();
        return view('pages.labelstock.index', ['datas' => $data,
         ]);
    }

    public function view(Request $request,$product_id,$location_id)

    {
      $data=StockByAgent::where('product_id',$product_id)->where('location_id',$location_id)->get();
      $stock_data=Stock::where('product_id',$product_id)->where('location_id',$location_id)->first();
       $location=BranchLocation::find($location_id);
      return view('pages.labelstock.view', ['datas' => $data,
        'location'=>$location->location??'',
        'store_balance'=>$stock_data->balance??'',
         ]);
    }
    public function StockbyLabelindex(Request $request)
    { 
      $route = Route::current();
      $id=$route->parameters();
      $label = Label::where('agent_id',$id['agent_id'] )
      ->where('location_id', $id['location_id'] )
      ->get();
      return view('pages.labelstock.label_product.index',['ids' => $id,'labels'=>$label ]);
    }
 public function StockbyLabelProduct(Request $request)
{ 
    try {
        $agent_id = $request->input("agent_id");
        $location_id = $request->input("location_id");
        $product_id = $request->input("product_id");
        $pageNumber = $request->has("page") ? $request->get("page") : 1;
        $perPage = $request->has("per_page") ? $request->get("per_page") : 10;
        $sort = $request->input("sort") ?? "desc";
        $label = Label::where('agent_id', $agent_id)
                      ->where('location_id', $location_id)
                      ->select('id')->pluck('id')->toArray();
                     
        if (!$label) {
            return $this->returnError("Label not found for given agent and location.");
        }
        $labelProductQuery = LabelProduct::with('label')->whereIn('label_id', $label)
                                          ->where('product_id', $product_id);
                                       
        $sort = $request->input("sort", "desc");
        $fieldSort = $request->input("field_sort", "id");
        $labelProductQuery->orderBy($fieldSort, $sort);
        $data = $labelProductQuery->paginate($perPage, ["*"], "page", $pageNumber);
        return $this->returnSuccess($data);
    } catch (\Exception $e) {
        return $this->returnError($e->getMessage());
    }
}

public function LabelProductBy(Request $request,$id,$product_id)
{ 
  $label=Label::find($id);
  $labelProduct = LabelProduct::where('label_id',  $label->id)
  ->where('product_id', $product_id)->get();
  return view('pages.labelstock.label_product.view',['label'=>$label,'product_id'=>$product_id,'labelProduct'=>$labelProduct]);
}
public function LabelProductByview(Request $request)
{ 
    try {
        $id = $request->input("id");
        $product_id = $request->input("product_id");
        $pageNumber = $request->input("page");
        $perPage = $request->input("per_page");
        $search = $request->input("search");
        $search_key = $request->input("search_key");
        $sort = $request->input("sort", "desc");
        $field_sort = $request->input("field_sort");
        $query = LabelProductDetail::with('label')
            ->where('label_id', $id)
            ->where('product_id', $product_id);
        if (!empty($search) && !empty($search_key)) {
            if ($search_key == "all") {
                $query->where(function ($q) use ($search) {
                    $q->where("issued", "like", "%" . $search . "%")
                      ->orWhere("consumed", "like", "%" . $search . "%")
                      ->orWhere("code", "like", "%" . $search . "%");
                });
            } else {
                $query->where($search_key, "like", "%" . $search . "%");
            }
        }
        if (!empty($field_sort) && in_array($field_sort, ['issued', 'consumed', 'damaged', 'adjust', 'returned'])) {
            $query->whereIn('status',[$field_sort]);
        }
        $query->orderBy('id', $sort);
        $data = $query->paginate($perPage, ["*"], "page", $pageNumber);
        return $this->returnSuccess($data);
    } catch (\Exception $e) {
        return $this->returnError($e->getMessage());
    }
}



}
