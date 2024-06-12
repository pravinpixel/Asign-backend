<?php

namespace App\Http\Controllers\Masters;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Country;
use App\Models\City;
class CityController extends Controller
{
  protected $pageNumber = 1;
  protected $perPage = 10;
  public function index(Request $request)
  {
      return view("pages.masters.city.index", []);
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
          $query = City::query();
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
      $title = 'Add City';
      $countries = Country::orderBy('name','asc')->get();
      $states = State::orderBy('name','asc')->get();
     if(isset($id) && !empty($id))
      {
          $info = City::find($id);
          $title = 'Edit City';
      }
       $content = view('pages.masters.city.add_edit_form',compact('states','countries','info','title'));
       return view('layouts.modal.dynamic_modal', compact('content', 'title'));
  }
  public function check(Request $request)
  {
      $id=$request->id;
      $validatedData = $request->validate([
          'name' => 'required|unique:cities,name,'.$id,           
          'code' => 'required|max:3|unique:cities,code,'.$id, 
          'status' => 'required|boolean',
          'state_id'=>'required|exists:states,id',
          'country_id'=>'required|exists:countries,id',],[
            'country_id.required' => ' The country field is required.', 
            'state_id.required' => ' The state field is required.',    
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
      $id=$request->id;
      try{
          if($id){
            $city=City::find($id);
                $city->name=$request->input('name');
                $city->state_id=$request->input('state_id');
                $city->code=$request->input('code');
                $city->is_serviceable=$request->input('is_serviceable');
                $city->status=$request->input('status');
                $city->update();  
          return $this->returnSuccess($city, "City  updated successfully");
      }else{  
            $city=new City;
            $city->name=$request->input('name');
            $city->state_id=$request->input('state_id');
            $city->country_id=$request->input('country_id');
            $city->code=$request->input('code');
            $city->is_serviceable=$request->input('is_serviceable');
            $city->status=$request->input('status');
            $city->save();    
          return $this->returnSuccess($city,"City  created successfully");
          }
      } catch (\Exception $e) {
          return $this->returnError($e->getMessage());
      }

    }
    public function delete(Request $request)
    {
        try {
          $id = $request->id;
            $City = City::find($id)->delete();
            return response()->json(['message' => 'City deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
        }

    }
    public function getState(Request $request)
    {       
      try{
        $country_id = $request->input('country');
        $states = State::where('country_id', $country_id)->get();
        return response()->json([
          'states' => $states
        ]);
      }catch (\Exception $e) {
        return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
      }
    }

}
