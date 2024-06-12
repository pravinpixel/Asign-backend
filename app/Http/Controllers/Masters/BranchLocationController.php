<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BranchLocation;
use Illuminate\Validation\Rule;

class BranchLocationController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;


    public function index(Request $request)
    {
        return view("pages.masters.branchlocation.index", []);
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
            $sort = $request->input('sort') ?? 'location|asc';
          if ($sort)
              $sort = explode('|', $sort);


            $field_sort = $request->input("field_sort");

            
            $query = BranchLocation::query();

            if (!empty($search) && !empty($search_key)) {
                if ($search_key == "all") {
                    $query->where("location", "like", "%" . $search . "%");
                } else {
                    $query->where($search_key, "like", "%" . $search . "%");
                }
            }

            if (!empty($field_sort)) {
                $query->orderBy($field_sort, $sort[1]);
            } else {
                $query->orderBy($sort[0], $sort[1]);
            }
            
            $data = $query->paginate($this->perPage, ["*"], "page", $this->pageNumber);
            return $this->returnSuccess($data);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function add_edit(Request $request)
    {
        $id = $request->id;
        $info = [];
        $title = "Add Asign Location";
        if (isset($id) && !empty($id)) {
            $info = BranchLocation::find($id);
            $title = "Edit Asign Location";
        }

        $content = view('pages.masters.branchlocation.add_edit_form', compact('info','title'));
        return view('layouts.modal.dynamic_modal', compact('content', 'title'));
    }


    public function check(Request $request)
    {
        $id = $request->id;
        $validatedData = $request->validate([
            'location' => [
                'required',
                Rule::unique('branch_locations')->ignore($id)->whereNull('deleted_at')
            ],
            'status' => 'required|boolean',
        ], [
            'location.unique' => 'This Location Already exists.',
        ]);
        try {
            return $this->returnSuccess(true);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function save(Request $request)
    {
        try {
            $id = $request->id;

            if ($id) {

                $data = BranchLocation::find($id);
                $data->location = $request->input('location');
                $data->status = $request->input('status');
                $data->update();

                return $this->returnSuccess($data, "Asign Location updated successfully");
            } else {
                $data = new BranchLocation;
                $data->location = $request->input('location');
                $data->status = $request->input('status');
                $data->save();

                return $this->returnSuccess($data, "Asign Location created successfully");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }




    public function delete(Request $request)
    {
        try {
            $id = $request->id;
            $master = BranchLocation::find($id)->delete();
            return response()->json(['message' =>"Asign Location deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
        }

    }
}
