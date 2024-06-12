<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;
    public function index(Request $request)
    {
        return view("pages.masters.size.index", []);
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
            $sort = $request->input('sort') ?? 'tag|asc';
            if ($sort)
                $sort = explode('|', $sort);
            $field_sort = $request->input("field_sort");
            $query = Size::query();
            if (!empty($search) && !empty($search_key)) {
                if ($search_key == "all") {
                    $query
                        ->where("tag", "like", "%" . $search . "%");       
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
        $title = 'Add Size';
       if(isset($id) && !empty($id))
        {
            $info = Size::find($id);
            $title = 'Edit Size';
        }
         $content = view('pages.masters.size.add_edit_form',compact('info','title'));
         return view('layouts.modal.dynamic_modal', compact('content', 'title'));
    }

    public function check(Request $request)
  {
    $id=$request->id;
    $validatedData = $request->validate([
        'tag' => 'required|string',
        'size_from' => 'required|integer',
        'size_to' => 'required|integer',
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
                $size =Size::find($id);
                $size->tag = $request->input('tag');
                $size->size_from = $request->input('size_from');
                $size->size_to = $request->input('size_to');
                $size->status = $request->input('status');
            $size->update();
            return $this->returnSuccess($size,"Size updated successfully");
            }
            else{
            $size = new Size;
            $size->tag = $request->input('tag');
            $size->size_from = $request->input('size_from');
            $size->size_to = $request->input('size_to');
            $size->status = $request->input('status');
            $size->save();
            return $this->returnSuccess($size,"Size created successfully");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }

      }
      public function delete(Request $request)
      {
          try {
            $id = $request->id;
              $size = Size::find($id)->delete();
              return response()->json(['message' => 'Size deleted successfully']);
          } catch (\Exception $e) {
              return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
          }
  
      }
}
