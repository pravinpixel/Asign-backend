<?php

namespace App\Http\Controllers\Masters;

use App\Models\Price;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;
    public function index(Request $request)
    {
        return view("pages.masters.price.index", []);
    }
    public function list(Request $request)
    {
        if ($request->has("page")) {
            $this->pageNumber = $request->get("page");
        }
        if ($request->has("per_page")) {
            $this->perPage = $request->get("per_page");
        }
        try {
            $search = $request->input("search");
            $search_key = $request->input("search_key");
            $sort = $request->input('sort') ?? 'price_from|asc';
            if ($sort)
                $sort = explode('|', $sort);
            $field_sort = $request->input("field_sort");
            $query = Price::query();
            if (!empty($search) && !empty($search_key)) {
                if ($search_key == "all") {
                    $query
                        ->where("price_from", "like", "%" . $search . "%");       
                } else {
                    $query->where($search_key, "like", "%" . $search . "%");
                }
            }
            $query->orderBy($sort[0], $sort[1]);
            $data = $query->paginate(
                $this->perPage,
                ["*"],
                "page",
                $this->pageNumber
            );
            return $this->returnSuccess($data);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }
     public function add_edit(Request $request)
    {
        $id = $request->id;
        $info = [];
        $title = 'Add Price';
       if(isset($id) && !empty($id))
        {
            $info = Price::find($id);
            $title = 'Edit Price';
        }
         $content = view('pages.masters.price.add_edit_form',compact('info','title'));
         return view('layouts.modal.dynamic_modal', compact('content', 'title'));
    }

    public function check(Request $request)
  {
    $id=$request->id;
    $validatedData = $request->validate([
        'price_from' => 'required|string|max:10',
        'price_to' => 'required|string|max:10',
        'status' => 'required|boolean'   
    ]);
      try {
          return $this->returnSuccess(true);
      } catch (\Exception $e) {
          return $this->returnError($e->getMessage());
      }
  }
    public function save(Request $request)
    {   
        
        try{
            $id=$request->id;
            if($id){
                $price =Price::find($id);
                $price->price_from = $request->input('price_from');
                $price->price_to = $request->input('price_to');
                $price->status = $request->input('status');
            $price->update();
            return $this->returnSuccess($price,"Price updated successfully");
            }
            else{
                $price = new Price();
                $price->price_from = $request->input('price_from');
                $price->price_to = $request->input('price_to');
                $price->status = $request->input('status');
                $price->save();
            return $this->returnSuccess($price,"Price created successfully");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }

      }
      public function delete(Request $request)
      {
          try {
            $id = $request->id;
              $price = Price::find($id)->delete();
              return response()->json(['message' => 'Price deleted successfully']);
          } catch (\Exception $e) {
              return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
          }
  
      }

}
