<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TimeZone;

class TimeZoneController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;
    public function index(Request $request)
    {
        return view("pages.masters.time.index", []);
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
            $query = TimeZone::query();
            if (!empty($search) && !empty($search_key)) {
                if ($search_key == "all") {
                    $query
                        ->where("name", "like", "%" . $search . "%")
                        ->orwhere("value", "like", "%" . $search . "%");
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
        $title = 'Add Time Zone';
       
       if(isset($id) && !empty($id))
        {
            $info = TimeZone::find($id);
            $title = 'Edit Time Zone';
        }
         $content = view('pages.masters.time.add_edit_form',compact('info','title'));
         return view('layouts.modal.dynamic_modal', compact('content', 'title'));
    }
    public function check(Request $request)
    {
        $id=$request->id;
        $validatedData = $request->validate([
            'name' => 'required',           
            'value' => 'required', 
            'status' => 'required|boolean']);
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
                  $city=TimeZone::find($id);
                  $city->name=$request->input('name');
                  $city->value=$request->input('value');
                  $city->status=$request->input('status');
                  $city->update();  
            return $this->returnSuccess($city, "Time Zone updated successfully");
        }else{  
              $city=new TimeZone;
              $city->name=$request->input('name');
              $city->value=$request->input('value');
              $city->status=$request->input('status');
              $city->save();    
            return $this->returnSuccess($city,"Time Zone created successfully");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
  
      }
      public function delete(Request $request)
      {
          try {
            $id = $request->id;
              $City = TimeZone::find($id)->delete();
              return response()->json(['message' => 'City deleted successfully']);
          } catch (\Exception $e) {
              return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
          }
  
      }
     
  
  }
  
