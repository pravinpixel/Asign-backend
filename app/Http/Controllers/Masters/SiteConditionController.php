<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteCondition;
use Illuminate\Validation\Rule;


class SiteConditionController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;
  
      public function index(Request $request)
      {
          return view("pages.masters.site-condition.index", []);
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
              $query = SiteCondition::query();
              if (!empty($search) && !empty($search_key)) {
                  if ($search_key == "all") {
                      $query->where("name", "like", "%" . $search . "%")
                          ->orWhere("question", "like", "%" . $search . "%")
                          ->orWhere("answer_type", "like", "%" . $search . "%");     
                  } 
                  else {
                      $query->where("name", "like", "%" . $search . "%")
                        ->orWhere("question", "like", "%" . $search . "%")
                          ->orWhere("answer_type", "like", "%" . $search . "%");     
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
          $title = 'Add SiteCondition';
         if(isset($id) && !empty($id))
          {
              $info = SiteCondition::find($id);
              $title = 'Edit SiteCondition';
          }
           $content = view('pages.masters.site-condition.add_edit_form',compact('info','title'));
           return view('layouts.modal.dynamic_modal', compact('content', 'title'));
      }
      public function check(Request $request)
      {
          $id=$request->id;
          $validatedData = $request->validate([
              'question' => 'required',    
              'answer_type' => 'required', 
              'name' => [
                'required',
                Rule::unique('site_conditions')->ignore($id, 'id')->where(function ($query) use ($request) {
                    return $query->where('question', $request->input('question'))
                                 ->where('answer_type', $request->input('answer_type'));
                }),
            ],            
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
              $siteCondition =SiteCondition::find($id);
              $siteCondition->name = $request->input('name');
              $siteCondition->answer_type = $request->input('answer_type');
              $siteCondition->question = $request->input('question');
              $siteCondition->status = $request->input('status');
              $siteCondition->update();
              return $this->returnSuccess($siteCondition,"SiteCondition updated successfully");
              }else{
  
              $siteCondition = new SiteCondition;
              $siteCondition->name = $request->input('name');
              $siteCondition->status = $request->input('status');
              $siteCondition->answer_type = $request->input('answer_type');
              $siteCondition->question = $request->input('question');
              $siteCondition->save();
              return $this->returnSuccess($siteCondition,"SiteCondition created successfully");
              }
          } catch (\Exception $e) {
              return $this->returnError($e->getMessage());
          }
  
        }
        public function delete(Request $request)
        {
            try {
              $id = $request->id;
                $siteCondition = SiteCondition::find($id)->delete();
                return response()->json(['message' => 'SiteCondition deleted successfully']);
            } catch (\Exception $e) {
                return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
            }
    
        }
}
