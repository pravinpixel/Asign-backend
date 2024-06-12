<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


class CommonController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;


    public function index(Request $request)
    {
        $data = $this->view($request);
        $routePrefix = $request->route()->getPrefix();
        return view("pages.masters.common.index", compact('data', 'routePrefix'));
    }

    private function view(Request $request)
    {
        $routePrefix = $request->route()->getPrefix();
        switch ($routePrefix) {
            case 'masters/artist':
                $data = [
                    'tableName' => "master_artists",
                    'modal' => "MasterArtist",
                    'title' => "Artist",
                    'breadcrumbs' => "Art Ecosystem"
                ];
                return $data;
            case 'masters/gallery':
                $data = [
                    'tableName' => "galleries",
                    'modal' => "Gallery",
                    'title' => "Gallery",
                    'breadcrumbs' => "Art Ecosystem"
                ];
                return $data;
            case 'masters/house':
                $data = [
                    'tableName' => "houses",
                    'modal' => "House",
                    'title' => "Auction House",
                    'breadcrumbs' => "Art Ecosystem"
                ];
                return $data;
            case 'masters/object-type':
                $data = [
                    'tableName' => "object_types",
                    'modal' => "ObjectType",
                    'title' => "Object Type",
                    'breadcrumbs' => "Artwork Details"
                ];
                return $data;
            case 'masters/medium':
                $data = [
                    'tableName' => "mediums",
                    'modal' => "Medium",
                    'title' => "Medium",
                    'breadcrumbs' => "Artwork Details"
                ];
                return $data;
            case 'masters/surface-medium':
                $data = [
                    'tableName' => "surface_mediums",
                    'modal' => "SurfaceMedium",
                    'title' => "Surface Medium",
                    'breadcrumbs' => "Artwork Details"
                ];
                return $data;
            case 'masters/measurement-type':
                $data = [
                    'tableName' => "measurement_types",
                    'modal' => "MeasurementType",
                    'title' => "Measurement Type",
                    'breadcrumbs' => "Artwork Details"
                ];
                return $data;
            case 'masters/shape':
                $data = [
                    'tableName' => "shapes",
                    'modal' => "Shape",
                    'title' => "Shape",
                    'breadcrumbs' => "Artwork Details"
                ];
                return $data;
            case 'masters/technique':
                $data = [
                    'tableName' => "techniques",
                    'modal' => "Technique",
                    'title' => "Technique",
                    'breadcrumbs' => "Artwork Details"
                ];
                return $data;
            case 'masters/style':
                $data = [
                    'tableName' => "styles",
                    'modal' => "Style",
                    'title' => "Style",
                    'breadcrumbs' => "Artwork Details"
                ];
                return $data;
            case 'masters/subject':
                $data = [
                    'tableName' => "subjects",
                    'modal' => "Subject",
                    'title' => "Subject",
                    'breadcrumbs' => "Artwork Details"
                ];
                return $data;
            // case 'masters/object-condition':
            //     $data = [
            //         'tableName' => "object_conditions",
            //         'modal' => "ObjectCondition",
            //         'title' => "Object Condition",
            //         'breadcrumbs' => "Others"
            //     ];
            //     return $data;    
            case 'masters/represenation-rejectedreason':
                $data = [
                    'tableName' => "represenation_rejected_reasons",
                    'modal' => "RepresenationRejectedReason",
                    'title' => "Represent Reason",
                    'breadcrumbs' => "Others"
                ];
                return $data; 
            case 'masters/movement':
                $data = [
                    'tableName' => "movements",
                    'modal' => "Movement",
                    'title' => "Movement",
                    'breadcrumbs' => "Artwork Details"
                ];
                return $data;
            case 'masters/acquisition-type':
                $data = [
                    'tableName' => "acquisition_types",
                    'modal' => "AcquisitionType",
                    'title' => "Acquisition Type",
                    'breadcrumbs' => "Provenance Details"
                ];
                return $data;
            case 'masters/insurance-type':
                $data = [
                    'tableName' => "insurance_types",
                    'modal' => "InsuranceType",
                    'title' => "Insurance Type",
                    'breadcrumbs' => "Provenance Details"

                ];
                return $data;
            case 'masters/branch-office':
                $data = [
                    'tableName' => "branch_locations",
                    'modal' => "BranchLocation",
                    'title' => "Branch Location",
                    'breadcrumbs' => "Stock"
                ];
                return $data;
            case 'masters/transporter':
                $data = [
                    'tableName' => "transporters",
                    'modal' => "Transporter",
                    'title' => "Transporter",
                    'breadcrumbs' => "Stock"
                ];
                return $data;
            case 'masters/manufacturer':
                $data = [
                    'tableName' => "manufacturers",
                    'modal' => "Manufacturer",
                    'title' => "Manufacturer",
                    'breadcrumbs' => "Stock"
                ];
                return $data;
            case 'masters/transfer-reason':
                $data = [
                    'tableName' => "transfer_reasons",
                    'modal' => "TransferReason",
                    'title' => "TransferReason",
                    'breadcrumbs' => "Stock"
                ];
                return $data;
            case 'masters/damage-type':
                $data = [
                    'tableName' => "damage_types",
                    'modal' => "DamageType",
                    'title' => "Damage Type",
                    'breadcrumbs' => "Stock"
                ];
                return $data;
            case 'masters/stockcheck-type':
                $data = [
                    'tableName' => "stock_check_types",
                    'modal' => "StockCheckType",
                    'title' => "StockCheck Type",
                    'breadcrumbs' => "Stock"
                ];
                return $data;
            case 'masters/product':
                $data = [
                    'tableName' => "products",
                    'modal' => "Product",
                    'title' => "Product",
                    'breadcrumbs' => "Stock"
                ];
                return $data;
            case 'masters/era':
                $data = [
                    'tableName' => "eras",
                    'modal' => "Era",
                    'title' => "Era",
                    'breadcrumbs' => "Others"
                ];
                return $data;
            case 'masters/genre':
                $data = [
                    'tableName' => "genres",
                    'modal' => "Genre",
                    'title' => "Genre",
                    'breadcrumbs' => "Others"
                ];
                return $data;
            case 'masters/period':
                $data = [
                    'tableName' => "periods",
                    'modal' => "Period",
                    'title' => "Period",
                    'breadcrumbs' => "Others"
                ];
                return $data;
            case 'masters/document-type':
                $data = [
                    'tableName' => "document_types",
                    'modal' => "DocumentType",
                    'title' => "Document Type",
                    'breadcrumbs' => "Others"
                ];
                return $data;
            case 'masters/report-condition':
                $data = [
                    'tableName' => "report_conditions",
                    'modal' => "ReportCondition",
                    'title' => "Report Condition",
                    'breadcrumbs' => "Others"
                ];
                return $data;
            case 'masters/condition-observation':
                $data = [
                    'tableName' => "condition_observations",
                    'modal' => "ConditionObservation",
                    'title' => "Condition Observation",
                    'breadcrumbs' => "Others"
                ];
                return $data;
            case 'masters/inscription':
                $data = [
                    'tableName' => "inscriptions",
                    'modal' => "Inscription",
                    'title' => "Inscription",
                    'breadcrumbs' => "Others"
                ];
                return $data;
            case 'masters/category':
                $data = [
                    'tableName' => "categories",
                    'modal' => "Category",
                    'title' => "Category",
                    'breadcrumbs' => "Others"
                ];
                return $data;
            case 'masters/exhibition-type':
                $data = [
                    'tableName' => "exhibition_types",
                    'modal' => "ExhibitionType",
                    'title' => "Exhibition Type",
                    'breadcrumbs' => "Others"
                ];
                return $data;
            case 'masters/rejected-reason':
                $data = [
                    'tableName' => "rejected_reasons",
                    'modal' => "RejectedReason",
                    'title' => "Rejected Reason",
                    'breadcrumbs' => "Others"
                ];
                return $data;
            case 'masters/coverage':
                $data = [
                    'tableName' => "coverage_types",
                    'modal' => "CoverageType",
                    'title' => "Coverage Type",
                    'breadcrumbs' => "Others"
                ];
                return $data;
            case 'masters/authenticator-checklist':
                $data = [
                    'tableName' => "authenticator_checklist_reasons",
                    'modal' => "AuthenticatorChecklistReason",
                    'title' => "Authenticator Reason",
                    'breadcrumbs' => "Others"
                ];
                return $data;
            case 'masters/void-reason':
                $data = [
                    'tableName' => "void_reasons",
                    'modal' => "VoidReason",
                    'title' => "Void Reason",
                    'breadcrumbs' => "Others"
                ];
                return $data;

            default:
                return [];
        }
    }

    public function list(Request $request)
    {
        $data = $this->view($request);
        $modal = $data['modal'];

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

            $modelClass = app("App\\Models\\{$modal}");
            $query = $modelClass::query();

            if (!empty($search) && !empty($search_key)) {
                if ($search_key == "all") {
                    $query->where("name", "like", "%" . $search . "%");
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
        $data = $this->view($request);
        $modal = $data['modal'];
        $heading = $data['title'];
        $modelClass = "App\\Models\\$modal";
        $id = $request->id;
        $info = [];
        $title = "Add $heading";
        if (isset($id) && !empty($id)) {
            $info = $modelClass::find($id);
            $title = $data['title'];
            $title = "Edit $heading";
        }

        $content = view('pages.masters.common.add_edit_form', compact('info', 'heading', 'title'));
        return view('layouts.modal.dynamic_modal', compact('content', 'title'));
    }


    public function check(Request $request)
    {
        $id = $request->id;
        $data = $this->view($request);
        $tableName = $data['tableName'];
        $validatedData = $request->validate([
            'name' => 'required|unique:' . $tableName . ',name,' . $id,
            'status' => 'required|boolean',
        ], [
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
        try {
            $data = $this->view($request);
            $modal = $data['modal'];
            $title = $data['title'];
            $modelClass = "App\\Models\\$modal";
            $id = $request->id;

            if (!class_exists($modelClass)) {
                return $this->returnError("Invalid module");
            }

            if ($id) {

                $data = $modelClass::find($id);
                $data->name = $request->input('name');
                $data->status = $request->input('status');
                $data->update();

                return $this->returnSuccess($data, "{$title} updated successfully");
            } else {
                $data = new $modelClass;
                $data->name = $request->input('name');
                $data->status = $request->input('status');
                $data->save();

                return $this->returnSuccess($data, "{$title} created successfully");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }




    public function delete(Request $request)
    {
        try {
            $data = $this->view($request);
            $modal = $data['modal'];
            $title = $data['title'];
            $id = $request->id;
            $modelClass = "App\\Models\\$modal";
            $master = $modelClass::find($id)->delete();
            return response()->json(['message' => $title . " deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
        }

    }
}
