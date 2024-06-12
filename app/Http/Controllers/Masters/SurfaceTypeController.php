<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SurfaceType;

class SurfaceTypeController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;
  
      public function index(Request $request)
      {
          return view("pages.masters.surfacetype.index", []);
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
              $sort = $request->input('sort') ?? 'name|asc';
              if ($sort)
                  $sort = explode('|', $sort);
              $field_sort = $request->input("field_sort");
              $query = SurfaceType::query();
              if (!empty($search) && !empty($search_key)) {
                  if ($search_key == "all") {
                      $query
                          ->where("name", "like", "%" . $search . "%")
                          ->orWhere("type", "like", "%" . $search . "%");     
                  } 
                  else {
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
          $title = 'Add Surface Type';
         if(isset($id) && !empty($id))
          {
              $info = SurfaceType::find($id);
              $title = 'Edit Surface Type';
          }
           $content = view('pages.masters.surfacetype.add_edit_form',compact('info','title'));
           return view('layouts.modal.dynamic_modal', compact('content', 'title'));
      }
      public function check(Request $request)
      {
          $id=$request->id;
          $validatedData = $request->validate([
            'name' => [
                'required',
                'unique:surface_types,name,NULL,id,type,' . $request->input('type')
            ],
            'type' => 'required',
            'status' => 'required|boolean',
        ], [
            'name.unique' => 'The combination of name and type must be unique.',
        ]);
        
          try {
              return $this->returnSuccess(true);
          } catch (\Exception $e) {
              return $this->returnError($e->getMessage());
          }
      }
      public function save(Request $request)
      {   
          $id=$request->id;
          try{
              if($id){
              $data =SurfaceType::find($id);
              $data->name = $request->input('name');
              $data->type = $request->input('type');
              $data->status = $request->input('status');
              $data->update();
              return $this->returnSuccess($data,"Surface Type updated successfully");
              }else{
  
              $data = new SurfaceType;
              $data->name = $request->input('name');
              $data->type = $request->input('type');
              $data->status = $request->input('status');
              $data->save();
              return $this->returnSuccess($data,"Surface Type created successfully");
              }
          } catch (\Exception $e) {
              return $this->returnError($e->getMessage());
          }
  
        }
        public function delete(Request $request)
        {
            try {
              $id = $request->id;
                $year = SurfaceType::find($id)->delete();
                return response()->json(['message' => 'Surface Type deleted successfully']);
            } catch (\Exception $e) {
                return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
            }
    
        }
  
  }
