<?php
namespace App\Http\Controllers\Masters;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Country;
use Validator;
class StateController extends Controller
{
  protected $pageNumber = 1;
  protected $perPage = 10;
  public function index(Request $request)
  {
      return view("pages.masters.state.index", []);
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
          $query = State::query();
          if (!empty($search) && !empty($search_key)) {
              if ($search_key == "all") {
                  $query
                      ->where("name", "like", "%" . $search . "%")
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
      $title = 'Add State';
      $countries = Country::orderBy('name','asc')->get();
     if(isset($id) && !empty($id))
      {
          $info = State::find($id);
          $title = 'Edit State';
      }
       $content = view('pages.masters.state.add_edit_form',compact('countries','info','title'));
       return view('layouts.modal.dynamic_modal', compact('content', 'title'));
  }
  public function check(Request $request)
  {
    $id=$request->id;
    $validatedData = $request->validate([
        'name' => 'required|unique:states,name,'.$id,           
        'code' => 'required|max:3|unique:states,code,'.$id, 
        'status' => 'required|boolean',  
        'country_id'=>'required|exists:countries,id',],[
          'country_id.required' => ' The country field is required.', 
          'name.unique' => ' This Name Already exist.',
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
      try{
        $id=$request->id;
          if($id){
          $state =State::find($id);
          $state->name = $request->input('name');
          $state->code = $request->input('code');
          $state->country_id = $request->input('country_id');
          $state->status = $request->input('status');
          $state->update();
          return $this->returnSuccess($state,"State updated successfully");
          }else{

          $state = new State;
          $state->name = $request->input('name');
          $state->code = $request->input('code');
          $state->country_id = $request->input('country_id');
          $state->status = $request->input('status');
          $state->save();
          return $this->returnSuccess($state,"State created successfully");
          }
      } catch (\Exception $e) {
          return $this->returnError($e->getMessage());
      }

    }
  
    public function delete(Request $request)
    {
        try {
          $id = $request->id;
            $state = State::find($id)->delete();
            return response()->json(['message' => 'State deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
        }

    }
    public function getState(Request $request)
    {
      try{
        $countryId = $request->input('country');
        $states = State::where('country_id', $countryId)
        ->get();
        return response()->json([
          'states' => $states
        ]);
      }catch (\Exception $e) {
        return response()->json(['status' => false, 'errors' => $e->getMessage()], 400);
      }
    }

}
