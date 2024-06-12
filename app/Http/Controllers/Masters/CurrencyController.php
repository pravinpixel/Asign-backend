<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;
    public function index(Request $request)
    {
        return view("pages.masters.currency.index", []);
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
            //$sort = $request->input("sort") ?? "asc";
            $sort = $request->input('sort') ?? 'name|asc';
            if ($sort)
                $sort = explode('|', $sort);

            $field_sort = $request->input("field_sort");
            $query = Currency::query();
            if (!empty($search) && !empty($search_key)) {
                if ($search_key == "all") {
                    $query
                        ->where("name", "like", "%" . $search . "%")
                        ->orWhere("symbol", "like", "%" . $search . "%")
                        ->orWhere("code", "like", "%" . $search . "%")
                        ->orWhere("exchange_rate", "like", "%" . $search . "%")
                        ->orWhere("is_base", "like", "%" . $search . "%");

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
        $title = 'Add Currency';
       if(isset($id) && !empty($id))
        {
            $info = Currency::find($id);
            $title = 'Edit Currency';
        }
         $content = view('pages.masters.currency.add_edit_form',compact('info','title'));
         return view('layouts.modal.dynamic_modal', compact('content', 'title'));
    }

    public function check(Request $request)
  {
    $id=$request->id;
    $validatedData = $request->validate([
        'name' => 'required|unique:currencies,name,'.$id,
        'code' => 'required|max:3',
        'symbol' => 'required',
        'status' => 'required|boolean', ],[
            'name.unique' => ' This Name Already exist.',
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
            $currency =Currency::find($id);
            $currency->name = $request->input('name');
            $currency->code = $request->input('code');
            $currency->symbol = $request->input('symbol');
            $currency->status = $request->input('status');
            $currency->update();
            return $this->returnSuccess($currency,"Currency updated successfully");
            }else{
            $currency = new Currency;
            $currency->name = $request->input('name');
            $currency->code = $request->input('code');
            $currency->symbol = $request->input('symbol');
            $currency->status = $request->input('status');
            $currency->save();
            return $this->returnSuccess($currency,"Currency created successfully");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }

      }


      public function delete(Request $request)
      {
          try {
            $id = $request->id;
              $currency = Currency::find($id)->delete();
              return response()->json(['message' => 'Currency deleted successfully']);
          } catch (\Exception $e) {
              return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
          }

      }
}
