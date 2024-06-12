<?php

namespace App\Http\Controllers\Masters;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fair;
use Illuminate\Support\Facades\Storage;

class FairController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;
    public function index(Request $request)
    {
        return view("pages.masters.fair.index", []);
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
            $query = Fair::query();
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
        $title = 'Add Fair';
        if (isset($id) && !empty($id)) {
            $info = Fair::find($id);
            $title = 'Edit Fair';
        }
        $content = view('pages.masters.fair.add_edit_form', compact('info', 'title'));
        return view('layouts.modal.dynamic_modal', compact('content', 'title'));
    }
    public function addEditAlt(Request $request, $id)
    {
        //$id = $request->id;
        $info = [];
        $title = 'Add Fair';
        if (isset($id) && !empty($id)) {
            $info = Fair::find($id);
            $title = 'Edit Fair';
        }

        return view('pages.masters.fair.form', compact('info', 'title'));
    }
    public function check(Request $request)
    {
        $id = $request->id;
        $validatedData = $request->validate([
            'name' => 'required|unique:fairs,name,' . $id,
            'from_date' => 'required',
            'to_date' => 'required',
            'status' => 'required|boolean',
        ], [
            'from_date.required' => 'From date field is required.',
            'to_date.required' => 'To date field is required.',
            'name.unique' => ' This Name Already exist.',
        ]);
        try {
            return $this->returnSuccess(true);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function save(Request $request)
    {
        $id = $request->id;
        try {
            if ($id) {
                $fair = Fair::find($id);
                $fair->name = $request->input('name');
                $fair->status = $request->input('status');
                $fair->from_date = $request->input('from_date');
                $fair->to_date = $request->input('to_date');
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $exten = $file->getClientOriginalExtension();
                    $fileName = time() . "." . $exten;

                    Storage::disk('s3')->put($fileName, file_get_contents($file));
                    $fair->image = $fileName; // Set the profile_image attribute
                }
                if ($request->input('image') == 'undefined') {
                    $fair->image = null;
                }
                $fair->update();
                return $this->returnSuccess($fair, "Fair updated successfully");
            } else {

                $fair = new Fair;
                $fair->name = $request->input('name');
                $fair->status = $request->input('status');
                $fair->from_date = $request->input('from_date');
                $fair->to_date = $request->input('to_date');
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $exten = $file->getClientOriginalExtension();
                    $fileName = time() . "." . $exten; // Define $fileName here
                    Storage::disk('s3')->put($fileName, file_get_contents($file));
                    $fair->image = $fileName; // Set the profile_image attribute
                }

                $fair->save();
                return $this->returnSuccess($fair, "Fair created successfully");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->id;
            $fair = Fair::find($id)->delete();
            return response()->json(['message' => 'Fair deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
        }
    }
}
