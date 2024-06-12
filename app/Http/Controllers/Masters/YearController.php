<?php
namespace App\Http\Controllers\Masters;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Year;
use Validator;
class YearController extends Controller
{
  protected $pageNumber = 1;
  protected $perPage = 10;

    public function index(Request $request)
    {
        return view("pages.masters.year.index", []);
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
            $sort = $request->input('sort') ?? 'year|asc';
            if ($sort)
                $sort = explode('|', $sort);
            $field_sort = $request->input("field_sort");
            $query = Year::query();
            if (!empty($search) && !empty($search_key)) {
                if ($search_key == "all") {
                    $query
                        ->where("year", "like", "%" . $search . "%");     
                } 
                else {
                    $query->where("year", "like", "%" . $search . "%");
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
        $title = 'Add Year';
       if(isset($id) && !empty($id))
        {
            $info = Year::find($id);
            $title = 'Edit Year';
        }
         $content = view('pages.masters.year.add_edit_form',compact('info','title'));
         return view('layouts.modal.dynamic_modal', compact('content', 'title'));
    }
    public function check(Request $request)
    {
        $id=$request->id;
        $validatedData = $request->validate([
            'year' => 'required|unique:years,year,'.$id,           
            'status' => 'required|boolean',  ],[
                'year.unique' => ' This Name Already exist.',      
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
            $year =Year::find($id);
            $year->year = $request->input('year');
            $year->status = $request->input('status');
            $year->update();
            return $this->returnSuccess($year,"Year updated successfully");
            }else{

            $year = new Year;
            $year->year = $request->input('year');
            $year->status = $request->input('status');
            $year->save();
            return $this->returnSuccess($year,"Year created successfully");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }

      }
      public function delete(Request $request)
      {
          try {
            $id = $request->id;
              $year = Year::find($id)->delete();
              return response()->json(['message' => 'Year deleted successfully']);
          } catch (\Exception $e) {
              return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
          }
  
      }

}
