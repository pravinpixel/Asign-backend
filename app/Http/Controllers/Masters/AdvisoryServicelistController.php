<?php

namespace App\Http\Controllers\Masters;
use App\Models\Advisoryservicelist;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdvisoryServicelistController extends Controller
{
  protected $pageNumber = 1;
  protected $perPage = 10;
  public function index(Request $request)
  {
      return view("pages.masters.advisoryservicelist.index", []);
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
          $sort = $request->input('sort') ?? 'title|asc';
          if ($sort)
              $sort = explode('|', $sort);
          $field_sort = $request->input("field_sort");
          $query = Advisoryservicelist::query();
          if (!empty($search) && !empty($search_key)) {
              if ($search_key == "all") {
                  $query
                      ->where("title", "like", "%" . $search . "%")
                      ->orwhere("cost", "like", "%" . $search . "%")
                      ->orwhere("code", "like", "%" . $search . "%");
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
          $title = 'Add Advisory Service List';
         if(isset($id) && !empty($id))
          {
              $info = Advisoryservicelist::find($id);
              $title = 'Edit Advisory Service List';
          }
         
           $content = view('pages.masters.advisoryservicelist.add_edit_form',compact('info','title'));
           return view('layouts.modal.dynamic_modal', compact('content', 'title'));
      }
        public function check(Request $request)
    {
        $id=$request->id;
        $validatedData = $request->validate([
            'code' => 'required|unique:advisory_service_lists,code,'.$id,           
            'status' => 'required|boolean',    
            'cost'        => 'required',
            'title'       => 'required',
            'description' => 'required',    ],[
                'code.unique' => ' This Code Already exist.',
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
              $servicelist =Advisoryservicelist::find($id);
              $servicelist->code        = $request->input('code');
              $servicelist->title       = $request->input('title');
              $servicelist->cost        = $request->input('cost');
              $servicelist->description = $request->input('description');
              $servicelist->status      = $request->input('status');
              $servicelist->update();
              return $this->returnSuccess($servicelist,"Advisory Service List updated successfully");
              }else{  
                $servicelist = new Advisoryservicelist;
                $servicelist->code        = $request->input('code');
                $servicelist->title       = $request->input('title');
                $servicelist->cost        = $request->input('cost');
                $servicelist->description = $request->input('description');
                $servicelist->status      = $request->input('status');
              $servicelist->save();
              return $this->returnSuccess($servicelist,"Advisory Service List created successfully");
              }
          } catch (\Exception $e) {
              return $this->returnError($e->getMessage());
          }
    
        }
        public function delete(Request $request)
        {
            try {
              $id = $request->id;
                $servicelist = Advisoryservicelist::find($id)->delete();
                return response()->json(['message' => 'Advisory Service List deleted successfully']);
            } catch (\Exception $e) {
                return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
            }
    
        }
}
