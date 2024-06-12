<?php

namespace App\Http\Controllers\Masters;
use App\Models\Location;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;

    public function index(Request $request)
    {
        return view("pages.masters.location.index", []);
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
            $query = Location::query();
            if (!empty($search) && !empty($search_key)) {
                if ($search_key == "all") {
                    $query
                        ->where("name", "like", "%" . $search . "%");
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
          $title = 'Add Location';
         if(isset($id) && !empty($id))
          {
              $info = Location::find($id);
              $title = 'Edit Location';
          }
           $content = view('pages.masters.location.add_edit_form',compact('info','title'));
           return view('layouts.modal.dynamic_modal', compact('content', 'title'));
      }

         public function check(Request $request)
    {
        $id = $request->id;
        $validatedData = $request->validate([
            'name' => 'required|unique:locations,name,'.$id,           
            'status' => 'required|boolean',       
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
          $validatedData = $request->validate([
              'name' => 'required|unique:locations,name,'.$id,           
              'status' => 'required|boolean',  ],[
                'name.unique' => ' This Name Already exist.',     
          ]);
          try{
              if($id){
              $location =Location::find($id);
              $location->name = $request->input('name');
              $location->status = $request->input('status');
              $location->update();
              return $this->returnSuccess($location,"Location updated successfully");
              }else{
                  
              $location = new Location;
              $location->name = $request->input('name');
              $location->status = $request->input('status');
              $location->save();
              return $this->returnSuccess($location,"Location created successfully");
              }
          } catch (\Exception $e) {
              return $this->returnError($e->getMessage());
          }
    
        }
      public function delete(Request $request,$id)
      {
        try{
          $location=Location::find($id)->delete();    
          return response()->json(['message' => 'location deleted successfully']);
         }catch (\Exception $e) {
         return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
         }
  
      }  
}
