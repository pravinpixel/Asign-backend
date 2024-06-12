<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ObjectType;
use App\Models\GstDetails;

class ObjectTypeController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;


    public function index(Request $request)
    {
        return view("pages.masters.object-type.index", []);
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

            
            $query = ObjectType::query()->with('gst');

            if (!empty($search) && !empty($search_key)) {
                if ($search_key == "all") {
                    $query->where("name", "like", "%" . $search . "%")
                    ->orWhere("percentage", "like", "%" . $search . "%");
                   
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
        $title = "Add Object Type";
        if (isset($id) && !empty($id)) {
            $info = ObjectType::find($id);
            $title = "Edit Object Type";
        }
        // gst dropdown
        $gstDetails = GstDetails::all()->where("status", "1");

        $content = view('pages.masters.object-type.add_edit_form', compact('info','title','gstDetails'));
        return view('layouts.modal.dynamic_modal', compact('content', 'title'));
    }


    public function check(Request $request)
    {
        $id = $request->id;
        $validatedData = $request->validate([
            'name' => 'required|unique:"object_types",name,' . $id,
            'gst' => 'required',
            'status' => 'required|boolean',
        ], [
            'name.unique' => ' This ObjectType Already exist.',
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

                $data = ObjectType::find($id);
                $data->name = $request->input('name');
                $data->gst_id = $request->input('gst');
                $data->status = $request->input('status');
                $data->update();

                return $this->returnSuccess($data, "ObjectType updated successfully");
            } else {
                $data = new ObjectType;
                $data->name = $request->input('name');
                $data->gst_id = $request->input('gst');
                $data->status = $request->input('status');
                $data->save();

                return $this->returnSuccess($data, "ObjectType created successfully");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }




    public function delete(Request $request)
    {
        try {
            $id = $request->id;
            $master = ObjectType::find($id)->delete();
            return response()->json(['message' =>"ObjectType deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
        }

    }
}
