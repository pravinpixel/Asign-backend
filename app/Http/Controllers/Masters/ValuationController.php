<?php

namespace App\Http\Controllers\Masters;

use App\Models\Valuation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ValuationController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;
    public function index(Request $request)
    {
        $tag = $request->route('tag');
        return view("pages.masters.valuation.index", ['tag'=>$tag]);
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
         $tag = $request->route('tag');
         $sort = $request->input('sort') ?? 'name|asc';
         if ($sort)
             $sort = explode('|', $sort);
        $field_sort = $request->input("field_sort");
        $query = Valuation::where('tag', $tag);

        if (!empty($search) && !empty($search_key)) { 
            if ($search_key == "all") {
                $query->where("name", "like", "%" . $search . "%");
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
        $tag = $request->route('tag');
        
        $id = $request->id;
        $info = [];
        $title = 'Add Valuation ' . ucfirst($tag);  
        if(isset($id) && !empty($id))
        {
            $info = Valuation::find($id);
            $title = 'Edit Valuation ' . ucfirst($tag);
        }
        $content = view('pages.masters.valuation.add_edit_form', compact('tag', 'info', 'title'));
        return view('layouts.modal.dynamic_modal', compact('content', 'title'));
    }
    public function check(Request $request)
    {
        $id=$request->id;
        $validatedData = $request->validate([
            'name' => 'required|unique:valuations,name,'.$id,           
            'status' => 'required|boolean',  ],[
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
        $tag = $request->route('tag');
        $id=$request->id;
        try{
            if($id){
            $valuation =Valuation::find($id);
            $valuation->name = $request->input('name');
            $valuation->status = $request->input('status');
            $valuation->tag = $tag;
            $valuation->update();
            return $this->returnSuccess($valuation, "Valuation $tag updated successfully");
        }else{  
            $valuation = new Valuation;
            $valuation->name = $request->input('name');
            $valuation->status = $request->input('status');
            $valuation->tag = $tag;
            $valuation->save();
            return $this->returnSuccess($valuation,"Valuation $tag created successfully");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
  
      }

    public function delete(Request $request)
    {
        try {
            $tag = $request->route('tag');
            $id = $request->id;
            $valuation = Valuation::where(['tag' => $tag, 'id' => $id])->first()->delete();
            return response()->json(['message' => 'Valuation deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
        }

    }
}
