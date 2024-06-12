<?php
namespace App\Http\Controllers\Masters;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use Validator;
class CountryController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;
    public function index(Request $request)
    {
        return view("pages.masters.country.index", []);
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
            $query = Country::query();
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
        $title = 'Add Country';
       if(isset($id) && !empty($id))
        {
            $info = Country::find($id);
            $title = 'Edit Country';
        }
         $content = view('pages.masters.country.add_edit_form',compact('info','title'));
         return view('layouts.modal.dynamic_modal', compact('content', 'title'));
    }
    public function check(Request $request)
    {
        $id = $request->id;
        $validatedData = $request->validate([
            'name' => 'required|unique:countries,name,'.$id,           
            'code' => 'required|max:3|unique:countries,code,'.$id, 
            'status' => 'required|boolean',    ],[
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
            $country =Country::find($id);
            $country->name = $request->input('name');
            $country->code = $request->input('code');
            $country->status = $request->input('status');
            $country->update();
            return $this->returnSuccess($country,"Country updated successfully");
            }else{

            $country = new Country;
            $country->name = $request->input('name');
            $country->code = $request->input('code');
            $country->status = $request->input('status');
            $country->save();
            return $this->returnSuccess($country,"Country created successfully");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
      }
      public function delete(Request $request)
      {
          try {
            $id = $request->id;
              $country = Country::find($id)->delete();
              return response()->json(['message' => 'Country deleted successfully']);
          } catch (\Exception $e) {
              return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
          }
  
      }
}
