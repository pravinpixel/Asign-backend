<?php

namespace App\Http\Controllers;

use stdClass;
use App\Helpers\UtilsHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\Artwork;
use App\Models\ArtworkComponent;
use App\Models\ArtworkExhibition;
use App\Models\ArtworkMedia;
use App\Models\ArtworkProtectActivityLog;
use App\Models\ArtworkProtectLocation;
use App\Models\ArtworkProtectRequest;
use App\Models\ArtworkProtectRequestsInspection;
use App\Models\AuthenticatorChecklistReason;
use App\Models\City;
use App\Models\VoidReason;
use App\Models\Medium;
use App\Models\ObjectCondition;
use App\Models\SiteCondition;
use App\Models\Style;
use App\Models\Surface;
use App\Models\SurfaceType;
use App\Models\Technique;
use App\Models\Label;
use App\Models\LabelProductDetail;
use App\Models\TempImage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\LabelVoid;
use Illuminate\Validation\ValidationException;

class ProtectRequestController extends Controller
{
    public function index(Request $request)
    {

        $user_id = auth()->user()->id;
        $role_id = auth()->user()->role_id;
        $role_filter = UtilsHelper::applyRoleFilter($role_id);
        $edit = true;

        $search = $request->input('search');
        $search_column = $request->input('search_column');
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $sort = $request->input('sort') ?? 'created_at|desc';

        $type = $request->input('type');
        $city = $request->input('city');
        $request_status = $request->input('status');
        $team = $request->input('team');

        if ($sort)
            $sort = explode('|', $sort);

        $status = ArtworkProtectRequest::STATUS;


        $data = DB::table('artwork_protect_requests as apr');

        if ($request_status) {
            if (in_array('review', $request_status))
                $request_status = array_merge($request_status, ['authentication-review', 'inspection-review', 'asign-protect-review']);
            $data->whereIn('apr.status', $request_status);
        }

        $data->join('customers as c', 'c.id', '=', 'apr.customer_id')
            ->join('artworks as a', 'a.id', '=', 'apr.artwork_id')
            ->leftJoin('cities as ct', 'ct.name', '=', 'a.city');

        if ($type)
            $data->whereIn('c.account_type', $type);
        if ($city)
            $data->whereIn('a.city', $city);

        if ($role_filter) {
            $edit = false;
            $column = $role_filter['column'];
            $data->whereRaw("FIND_IN_SET($user_id, $column)");
            if ($role_filter['verified_status']) {
                $verify_column = "status_timeline->{$role_filter['verified_status']}->approve";
                $data->whereJsonContains($verify_column, true);
            }

            $auth_access = access()->hasAccess('authentication-request.view');
            $inspection_access = access()->hasAccess('inspection-request.view');
            $asign_access = access()->hasAccess('label-requests.view');
            $data->where(function ($query) use ($auth_access, $inspection_access, $asign_access) {
                if ($auth_access)
                    $query->orWhere('apr.status', 'authentication');
                if ($inspection_access)
                    $query->orWhere('apr.status', 'inspection');
                if ($asign_access)
                    $query->orWhere('apr.status', 'asign-protect');
            });
        }

        if ($team) {
            $data->where(function ($query) use ($team) {
                foreach ($team as $team_id) {
                    $query->orWhereRaw("FIND_IN_SET($team_id, authenticator_ids)");
                    $query->orWhereRaw("FIND_IN_SET($team_id, conservator_ids)");
                    $query->orWhereRaw("FIND_IN_SET($team_id, field_agent_ids)");
                    $query->orWhereRaw("FIND_IN_SET($team_id, service_provider_ids)");
                }
            });
        }

        $data = $data->select('apr.id', 'apr.request_id', 'apr.status', 'apr.authenticator_ids', 'apr.field_agent_ids', 'apr.conservator_ids', 'apr.status_timeline', 'apr.reviewer_id', 'apr.service_provider_ids', 'apr.inspection_date', 'apr.inspection_time', 'apr.created_at', 'c.full_name', 'c.aa_no', 'c.account_type', 'ct.id as city_id', 'a.city', UtilsHelper::checkOnline())
            ->where(function ($query) use ($search, $search_column) {
                if ($search_column == 'request_id' || !$search_column)
                    $query->orWhere('apr.request_id', 'like', "%$search%");
                if ($search_column == 'aa_no' || !$search_column)
                    $query->orWhere('c.aa_no', 'like', "%$search%");
                if ($search_column == 'status' || !$search_column)
                    $query->orWhere('apr.status', 'like', "%$search%");
            })
            ->orderBy($sort[0], $sort[1])
            ->paginate($per_page, ['*'], 'page', $page);

        $total = $data->total();
        $roles = UtilsHelper::roleWiseUser();

        if ($request->ajax()) {
            return [
                'table' => view('pages.protect_request.table', compact('data', 'status', 'roles', 'edit'))->render(),
                'pagination' => view('components.pagination', compact('data'))->render(),
                'total' => $total,
            ];
        }

        //$cities = Customer::select('city')->whereNotNull('city')->groupBy('city')->get()->pluck('city');

        $cities = City::where(['status' => 1, 'is_serviceable' => 1])->get(['id', 'name']);

        return view('pages.protect_request.index', compact('data', 'status', 'total', 'roles', 'cities', 'edit'));
    }

    public function show($id = null)
    {
        $user_id = auth()->user()->id;
        $role_id = auth()->user()->role_id;
        $role_filter = UtilsHelper::applyRoleFilter($role_id);

        $auth_access = access()->hasAccess('authentication-request.view');
        $inspection_access = access()->hasAccess('inspection-request.view');
        $asign_access = access()->hasAccess('label-requests.view');

        if (!$auth_access && !$inspection_access && !$asign_access)
            return view('403');

        $protect_request = ArtworkProtectRequest::where('id', $id);
        if ($role_filter) {
            $column = $role_filter['column'];
            $protect_request->whereRaw("FIND_IN_SET($user_id, $column)");
            if ($role_filter['verified_status']) {
                $verify_column = "status_timeline->{$role_filter['verified_status']}->approve";
                $protect_request->whereJsonContains($verify_column, true);
            }
        }
        $protect_request = $protect_request->first();
        if (!$protect_request) return $this->returnError('Request not found');

        $artwork = $protect_request->artwork;
        if (!$artwork) return $this->returnError('Artwork not found');

        $customer = $protect_request->customer;
        $address = '';

        if ($customer?->address_line1)
            $address .= $customer?->address_line1;
        if ($customer?->address_line2)
            $address .= ', ' . $customer?->address_line2;
        if ($customer?->city)
            $address .= ', ' . $customer?->city;
        if ($customer?->state)
            $address .= ', ' . $customer?->state?->name;
        if ($customer?->country)
            $address .= ', ' . $customer?->country?->name;
        if ($customer?->pin_code)
            $address .= ', ' . $customer?->pin_code;

        $techniques = $styles = $mediums = $surfaces = [];

        if ($artwork->technique_ids) {
            if (is_array($artwork->technique_ids))
                $technique_ids = $artwork->technique_ids;
            else
                $technique_ids = explode(',', $artwork->technique_ids);
            $techniques = Technique::whereIn('id', $technique_ids)->get(['id', 'name']);
        }
        if ($artwork->style_ids) {
            if (is_array($artwork->style_ids))
                $style_ids = $artwork->style_ids;
            else
                $style_ids = explode(',', $artwork->style_ids);
            $styles = Style::whereIn('id', $style_ids)->get(['id', 'name']);
        }
        if ($artwork->medium) {
            if (is_array($artwork->medium))
                $medium_ids = $artwork->medium;
            else
                $medium_ids = explode(',', $artwork->medium);
            $mediums = Medium::whereIn('id', $medium_ids)->get(['id', 'name']);
        }
        if ($artwork->surface) {
            if (is_array($artwork->surface))
                $surface_ids = $artwork->surface;
            else
                $surface_ids = explode(',', $artwork->surface);
            $surfaces = Surface::whereIn('id', $surface_ids)->get(['id', 'name']);
        }

        $location = '';

        $primary_location = [];

        if ($artwork->location_details) {
            $city = $artwork->location_details->city;
            $state = $artwork->location_details->state?->name;
            $country = $artwork->location_details->country?->name;
            $pin_code = $artwork->location_details->pin_code;
            if ($city)
                $location .= $city;
            if ($state)
                $location .= ", " . $state;
            if ($country)
                $location .= ", " . $country;
            if ($pin_code)
                $location .= "- " . $pin_code;
            $primary_location[] = [
                'id' => -1,
                "city" => $city,
                "state" => $artwork->location_details->state,
                "country" => $artwork->location_details->country,
                "pin_code" => $pin_code,
                "location_as" => $artwork->location_details->location_as,
                "sub_location" => $artwork->location_details->sub_location,
                "address_line1" => $artwork->location_details->address_line1,
                "address_line2" => $artwork->location_details->address_line2,
            ];
        }


        $data = [
            'id' => $protect_request->id,
            'city' => $artwork->city,
            'authenticator_ids' => $protect_request->authenticator_ids,
            'conservator_ids' => $protect_request->conservator_ids,
            'field_agent_ids' => $protect_request->field_agent_ids,
            'service_provider_ids' => $protect_request->service_provider_ids,
            'reviewer_id' => $protect_request->reviewer_id,
            'inspection_date' => $protect_request->inspection_date,
            'inspection_time' => $protect_request->inspection_time,
            'visit_date' => $protect_request->visit_date,
            'visit_time' => $protect_request->visit_time,
            'request_id' => $protect_request->request_id,
            'title' => $artwork->title,
            'is_your_possession' => $artwork->is_your_possession,
            'type' => $artwork->type,
            'type_others' => $artwork->type_others,
            'artist' => $artwork->artist ?? null,
            'unknown_artist' => $artwork->unknown_artist,
            'description' => $artwork->description,
            'creation_year_from' => $artwork->creation_year_from,
            'creation_year_to' => $artwork->creation_year_to,
            'provenances' => $artwork->provenances,
            'asign_no' => $artwork->asign_no,
            'accession_no' => $artwork->accession_no,
            'inventory_no' => $artwork->inventory_no,
            'customer_address' => $address,
            'customer_id' => $customer?->id,
            'customer_name' => $customer?->display_name,
            'customer_account_type' => $customer?->account_type,
            'customer_mobile' => $customer?->mobile,
            'shape' => $artwork->shape,
            'measurement_type' => $artwork->measurementType,
            'subject' => $artwork->subject,
            'movement' => $artwork->movement,
            'styles' => $styles,
            'techniques' => $techniques,
            'is_signature' => $artwork->is_signature,
            'is_inscription' => $artwork->is_inscription,
            'signature' => $artwork->signature,
            'recto_inscription' => $artwork->recto_inscription,
            'verso_inscription' => $artwork->verso_inscription,
            'base_inscription' => $artwork->base_inscription,
            'dimension_size' => $artwork->dimension_size,
            'height' => $artwork->height,
            'width' => $artwork->width,
            'depth' => $artwork->depth,
            'diameter' => $artwork->diameter,
            'weight' => $artwork->weight,
            'weight_size' => $artwork->weight_size,
            'mediums' => $mediums,
            'surfaces' => $surfaces,
            'medium_others' => $artwork->medium_others,
            'surface_others' => $artwork->surface_others,
            'subject_others' => $artwork->subject_others,
            'movement_others' => $artwork->movement_others,
            'style_others' => $artwork->style_others,
            'technique_others' => $artwork->technique_others,
            'location' => $location,
            'locations' => $primary_location,
            'secondary_locations' => $protect_request->secondaryLocations,
            'components' => $artwork->components,
            'auctions' => $artwork->auctions,
            'images' => $artwork->images,
            'publications' => $artwork->publications,
            'activities' => $protect_request->activities,
            'status' => ArtworkProtectRequest::STATUS[$protect_request->status] ?? null,
            'verify_status' => $protect_request->verify_status ?? null,
            'reference_img_url' => $protect_request->reference_img_url,
            'current_step' => $protect_request->current_step
        ];

        if ($protect_request->status == 'asign-protect') {
            $data['button_verify'] = $this->asignProtectApproveVerify($protect_request, $protect_request->verify_status);
        }

        $data['labelling'] = $this->processObjectLabelling($protect_request);

        if ($artwork->dimension_size == 'cm') {
            $data['height'] = $artwork->height_cm;
            $data['width'] = $artwork->width_cm;
            $data['depth'] = $artwork->depth_cm;
            $data['diameter'] = $artwork->diameter_cm;
        }

        $exhibition_query = ArtworkExhibition::where('artwork_id', $artwork->id)->with('exhibition')->get();
        $exhibitions = [];
        foreach ($exhibition_query as $key => $value) {
            $tmp = $value->exhibition;
            $exhibitions[] = $tmp;
        }
        $data = array_merge($data, [
            'exhibitions' => $exhibitions,
        ]);

        $inspection = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $id)->first();
        $inspection_object_condition = ObjectCondition::where('status', 1)->where('question', 'object_match')->where('answer_type', 'yes')->get(['id', 'name'])->toArray();
        $inspection_object_condition_no = ObjectCondition::where('status', 1)->where('question', 'object_match')->where('answer_type', 'no')->get(['id', 'name'])->toArray();
        $inspection_object_noticeable_damage_yes = ObjectCondition::where('status', 1)->where('question', 'damage')->where('answer_type', 'yes')->get(['id', 'name'])->toArray();
        $inspection_object_asign_protect_no = ObjectCondition::where('status', 1)->where('question', 'asign_protect+')->where('answer_type', 'no')->get(['id', 'name'])->toArray();
        $inspection_surface_type_frame = SurfaceType::where(['status' => 1, 'type' => 'Frame'])->get(['id', 'name'])->toArray();
        $inspection_surface_type_strecher = SurfaceType::where(['status' => 1, 'type' => 'Strecher'])->get(['id', 'name'])->toArray();
        $inspection_surface_type_object_stand = SurfaceType::where(['status' => 1, 'type' => 'Object Stand'])->get(['id', 'name'])->toArray();
        $inspection_object_surface_suitable_no = ObjectCondition::where('status', 1)->where('question', 'surface_condition')->where('answer_type', 'no')->get(['id', 'name'])->toArray();
        $inspection_site_adequatephysical_taskcomplete_no = SiteCondition::where('status', 1)->where('question', 'physical_space')->where('answer_type', 'no')->get(['id', 'name'])->toArray();
        $inspection_site_smoothworkflow_no = SiteCondition::where('status', 1)->where('question', 'site_accessible')->where('answer_type', 'no')->get(['id', 'name'])->toArray();
        $inspection_site_lighting_adequate_no = SiteCondition::where('status', 1)->where('question', 'lighting_adequate')->where('answer_type', 'no')->get(['id', 'name'])->toArray();
        $inspection_site_surrounding_workspace_no = SiteCondition::where('status', 1)->where('question', 'surrounding_work_space')->where('answer_type', 'no')->get(['id', 'name'])->toArray();
        $inspection_site_condition_request_data = $this->inspectionSiteConditionRequestData($protect_request, $artwork->city);
        $inspection_site_condition_request_data_in_inspection = $this->inspectionSiteConditionRequestData($protect_request, $artwork->city, 'inspection');
        $provenance_reason = AuthenticatorChecklistReason::where('status', 1)->get(['id', 'name'])->toArray();
        $reasons = VoidReason::where('status', 1)->get();

        $data_asign_link = [];
        if (!empty($inspection['provenance_object_number'])) {
            $data_asign_link = DB::table('artwork_protect_requests as apr')
                ->join('artworks as a', 'a.id', '=', 'apr.artwork_id')
                ->leftJoin('customers as c', 'c.id', '=', 'a.artist_id')
                ->where('a.asign_no', '=', $inspection['provenance_object_number'])
                ->where('apr.id', '=', $id)->select('a.asign_no', 'a.artist_id', 'a.unknown_artist', 'a.title', 'a.creation_year_from', 'c.full_name')->get();
        }
        //dd($inspection['provenance_object_number']);
        $data = array_merge($data, [
            'inspection' => $inspection,
            'inspection_object_condition' => $inspection_object_condition,
            'inspection_object_condition_no' => $inspection_object_condition_no,
            'inspection_object_noticeable_damage_yes' => $inspection_object_noticeable_damage_yes,
            'inspection_object_asign_protect_no' => $inspection_object_asign_protect_no,
            'inspection_surface_type_frame' => $inspection_surface_type_frame,
            'inspection_surface_type_strecher' => $inspection_surface_type_strecher,
            'inspection_surface_type_object_stand' => $inspection_surface_type_object_stand,
            'inspection_object_surface_suitable_no' => $inspection_object_surface_suitable_no,
            'inspection_site_adequatephysical_taskcomplete_no' => $inspection_site_adequatephysical_taskcomplete_no,
            'inspection_site_smoothworkflow_no' => $inspection_site_smoothworkflow_no,
            'inspection_site_lighting_adequate_no' => $inspection_site_lighting_adequate_no,
            'inspection_site_surrounding_workspace_no' => $inspection_site_surrounding_workspace_no,
            'inspection_site_condition_request_data' => $inspection_site_condition_request_data,
            'inspection_site_condition_request_data_in_inspection' => $inspection_site_condition_request_data_in_inspection,
            'provenance_reason' => $provenance_reason,
            'provenance_reason_confirm_object_link' => (!empty($data_asign_link[0]) ? (!empty($data_asign_link[0]->artist_id) ? $data_asign_link[0]->full_name : $data_asign_link[0]->unknown_artist) . '-' . $data_asign_link[0]->title . ',' . $data_asign_link[0]->creation_year_from : '')
        ]);

        $role_arr = ['authenticator', 'field_agent', 'conservator', 'service_provider', 'supervisor'];
        $roles = UtilsHelper::roleWiseUser($role_arr, $artwork->city, true);

        $master = UtilsHelper::masterData();

        return view('pages.protect_request.view', compact('data', 'roles', 'master', 'reasons'));
    }


    public function showObject(Request $request)
    {

        $id = $request->route('id');
        $route_name = $request->route()->getName();

        $protect_request = ArtworkProtectRequest::find($id);
        if (!$protect_request) return $this->returnError('Request not found');

        $artwork = $protect_request->artwork;
        if (!$artwork) return $this->returnError('Artwork not found');

        $edit = true;
        if ($route_name != "protect-request.view-object") {
            $edit = false;
            $artworkSaveColumn = [
                'creation_year_from', 'creation_year_to', 'medium', 'surface', 'shape_id', 'measurement_type_id',
                'dimension_size', 'signature', 'description', 'is_inscription', 'signature',
                'recto_inscription', 'verso_inscription', 'base_inscription'
            ];

            foreach ($artworkSaveColumn as $key => $value) {
                $input_value = $request->input($value);
                if ($value == 'medium' || $value == 'surface') {
                    if (is_array($input_value))
                        $artwork->$value = implode(',', $input_value);
                    else
                        $artwork->$value = null;
                } else
                    $artwork->$value = $input_value;
            }

            $dimensions = [
                'dimension_size' => request()->input('dimension_size'),
                'height' => request()->input('height'),
                'width' => request()->input('width'),
                'depth' => request()->input('depth'),
                'diameter' => request()->input('diameter'),
            ];

            $dimensions_result = UtilsHelper::convertSize($dimensions);
            foreach ($dimensions_result as $key => $value) {
                $artwork->$key = $value;
            }

            $artwork->save();
        }

        $techniques = $styles = $mediums = $surfaces = [];

        if ($artwork->technique_ids) {
            if (is_array($artwork->technique_ids))
                $technique_ids = $artwork->technique_ids;
            else
                $technique_ids = explode(',', $artwork->technique_ids);
            $techniques = Technique::whereIn('id', $technique_ids)->get(['id', 'name']);
        }
        if ($artwork->style_ids) {
            if (is_array($artwork->style_ids))
                $style_ids = $artwork->style_ids;
            else
                $style_ids = explode(',', $artwork->style_ids);
            $styles = Style::whereIn('id', $style_ids)->get(['id', 'name']);
        }
        if ($artwork->medium) {
            if (is_array($artwork->medium))
                $medium_ids = $artwork->medium;
            else
                $medium_ids = explode(',', $artwork->medium);
            $mediums = Medium::whereIn('id', $medium_ids)->get(['id', 'name']);
        }
        if ($artwork->surface) {
            if (is_array($artwork->surface))
                $surface_ids = $artwork->surface;
            else
                $surface_ids = explode(',', $artwork->surface);
            $surfaces = Surface::whereIn('id', $surface_ids)->get(['id', 'name']);
        }

        $data = [
            'id' => $protect_request->id,
            'request_id' => $protect_request->request_id,
            'title' => $artwork->title,
            'is_your_possession' => $artwork->is_your_possession,
            'type' => $artwork->type,
            'artist' => $artwork->artist ?? null,
            'unknown_artist' => $artwork->unknown_artist,
            'description' => $artwork->description,
            'creation_year_from' => $artwork->creation_year_from,
            'creation_year_to' => $artwork->creation_year_to,
            'shape' => $artwork->shape,
            'measurement_type' => $artwork->measurementType,
            'subject' => $artwork->subject,
            'movement' => $artwork->movement,
            'styles' => $styles,
            'techniques' => $techniques,
            'is_signature' => $artwork->is_signature,
            'is_inscription' => $artwork->is_inscription,
            'signature' => $artwork->signature,
            'recto_inscription' => $artwork->recto_inscription,
            'verso_inscription' => $artwork->verso_inscription,
            'base_inscription' => $artwork->base_inscription,
            'dimension_size' => $artwork->dimension_size,
            'height' => $artwork->dimension_size == 'cm' ? $artwork->height_cm : $artwork->height,
            'width' => $artwork->dimension_size == 'cm' ? $artwork->width_cm : $artwork->width,
            'depth' => $artwork->dimension_size == 'cm' ? $artwork->depth_cm : $artwork->depth,
            'diameter' => $artwork->dimension_size == 'cm' ? $artwork->diameter_cm : $artwork->diameter,
            'weight' => $artwork->weight,
            'weight_size' => $artwork->weight_size,
            'mediums' => $mediums,
            'surfaces' => $surfaces,
            'medium_ids' => $artwork->medium,
            'surface_ids' => $artwork->surface,
            'status' => ArtworkProtectRequest::STATUS[$protect_request->status] ?? null,
            'verify_status' => $protect_request->verify_status ?? null,
        ];

        $master = UtilsHelper::masterData();

        $result = [
            'about' => view('pages.protect_request.components.about', compact('data', 'edit'))->render(),
            'medium' => view('pages.protect_request.components.medium', compact('data', 'edit', 'master'))->render(),
            'signature' => view('pages.protect_request.components.signature', compact('data', 'edit'))->render(),
        ];

        return $this->returnSuccess($result, 'Detail get successfully');
    }


    public function changeTeam(Request $request)
    {
        $id = $request->route('id');
        try {
            $validator = $request->validate([
                'team' => 'nullable|string|max:1000',
                'date' => 'nullable|date',
            ]);

            $team = $request->input('team');
            $date = $request->input('date');

            $protect_request = ArtworkProtectRequest::find($id);
            if (!$protect_request) return $this->returnError('Request not found');

            if ($protect_request->status == 'authentication') {
                $protect_request->authenticator_ids = $team;
            } else if ($protect_request->status == 'inspection') {
                //   $protect_request->conservator_ids = $team;

            } else if ($protect_request->status == 'asign-protect') {
                // $protect_request->service_provider_ids = $team;
                //   $protect_request->visit_date = $date;
            }
            $protect_request->inspection_date = $date;
            $protect_request->save();
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess([
            'date' => UtilsHelper::displayDate($protect_request->inspection_date, 'l, d M, Y'),
            'time' => $protect_request->inspection_time,
        ], 'Team updated successfully');
    }

    public function team(Request $request)
    {

        $id = $request->route('id');

        $rules_arr = [
            'authenticator_ids' => 'nullable|array',
            'conservator_ids' => 'nullable|array',
            'field_agent_ids' => 'nullable|array',
            'service_provider_ids' => 'nullable|array',
            'inspection_date' => 'nullable',
            'inspection_time' => 'nullable',
            'visit_date' => 'nullable',
            'visit_time' => 'nullable',
        ];

        try {

            $result = array_intersect_key($rules_arr, $request->all());

            $protect_request = ArtworkProtectRequest::find($id);
            if (!$protect_request) return $this->returnError('Request not found');
            $validator = $request->validate($result);
            foreach ($result as $key => $value) {
                if (is_array($request->$key))
                    $protect_request->$key = implode(',', $request->$key);
                else
                    $protect_request->$key = $request->$key;
            }
            $protect_request->save();

            $result = [
                'header' => $this->headerDataView($protect_request),
            ];
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess($result, 'Request updated successfully');
    }

    public function verify(Request $request)
    {
        $id = $request->route('id');

        try {
            $user_id = auth()->user()->id;
            $type = $request->input('type');
            $type_id = $request->input('type_id');
            $verify = $request->input('verify');
            $column = 'field_agent_ids';
            if ($type == "auction" || $type == "publication" || $type == "exhibition")
                $column = 'authenticator_ids';

            $protect_request = ArtworkProtectRequest::where('id', $id)
                ->whereRaw("FIND_IN_SET($user_id, $column)")->first();

            if (!$protect_request) return $this->returnError('You are not authorized to verify this request');


            if (!in_array($protect_request->status, ['asign-protect', 'authentication']))
                return $this->returnError('Please reload the page and try again');

            $verify_status = $protect_request->verify_status ?? [];
            $data = ['verify' => $verify, 'date' => now(), 'user_id' => auth()->user()->id];

            if (isset($verify_status[$type][$type_id]))
                unset($verify_status[$type][$type_id]);

            $verify_status[$type][$type_id] = $data;
            $protect_request->verify_status = $verify_status;

            $protect_request->save();

            $data = [];
            $data['labelling'] = $this->processObjectLabelling($protect_request);

            $result = [
                'status' => $protect_request->status,
                'header' => $this->headerDataView($protect_request),
                'object-label' => view('pages.protect_request.components.object-label', compact('data'))->render()
            ];
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        return $this->returnSuccess($result, 'Verify status updated successfully');
    }

    public function location(Request $request)
    {
        $id = $request->route('id');

        try {
            $validator = $request->validate([
                // 'location_as' => 'required|string|max:1000',
                'sub_location' => 'required|string|max:1000',
                'address_line1' => 'required|string|max:1000',
                //   'address_line2' => 'required|string|max:1000',
                'city' => 'required|string|max:50',
                'pin_code' => 'required|string|max:10',
                'state_id' => 'required|exists:states,id',
                'country_id' => 'required|exists:countries,id',
            ]);

            $user_id = auth()->user()->id;
            $validator['request_id'] = $id;
            $validator['location_as'] = '';
            $validator['created_by'] = $user_id;

            $protect_request = ArtworkProtectRequest::where('id', $id)
                ->whereRaw("FIND_IN_SET($user_id, field_agent_ids)")->first();
            if (!$protect_request) return $this->returnError('You are not authorized to add location');

            if ($protect_request->status != 'asign-protect')
                return $this->returnError('Please reload the page and try again');

            $id = ArtworkProtectLocation::create($validator);

            $key = $protect_request->secondaryLocations()->count() - 1;

            $data = ['locations' => [$id]];
            $result = view('pages.protect_request.components.locations', compact('data', 'key'))->render();
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        return $this->returnSuccess($result, 'Location added successfully');
    }

    public function component(Request $request)
    {
        $id = $request->route('id');
        $component_id = $request->input('component_id');

        try {
            $validator = $request->validate([
                'accession_no' => 'nullable|string|max:20',
                'technique_used' => 'nullable|array',
                'technique_others' => 'nullable|string|max:100',
                'is_signature' => 'nullable|boolean',
                'signature' => 'nullable|string|max:2000',
                'is_inscription' => 'nullable|boolean',
                'recto_inscription' => 'nullable|string|max:2000',
                'verso_inscription' => 'nullable|string|max:2000',
                'base_inscription' => 'nullable|string|max:2000',
                'cover_image' => 'nullable',
                'location_as' => 'nullable|string|max:1000',
                'sub_location' => 'nullable|string|max:1000',
                'address_line1' => 'nullable|string|max:1000',
                'address_line2' => 'nullable|string|max:1000',
                'city' => 'nullable|string|max:50',
                'state_id' => 'nullable|integer|exists:states,id',
                'mediums' => 'nullable|array',
                'medium_other' => 'nullable|string|max:100',
                'surface_other' => 'nullable|string|max:100',
                'surface' => 'nullable|array',
                'country_id' => 'nullable|integer|exists:countries,id',
                'pin_code' => 'nullable|string|max:10',
                'measurement_type_id' => 'nullable|integer|exists:measurement_types,id',
                'shape_id' => 'nullable|integer|exists:shapes,id',
                'dimension_size' => 'nullable|string|in:cm,m,in',
                'height' => 'nullable|string|max:11',
                'width' => 'nullable|string|max:11',
                'depth' => 'nullable|string|max:11',
                'diameter' => 'nullable|string|max:11',
                'weight_size' => 'nullable|string|in:kg,lbs',
                'weight' => 'nullable|string|max:4',
            ]);

            $user_id = auth()->user()->id;
            $protect_request = ArtworkProtectRequest::where('id', $id)
                ->whereRaw("FIND_IN_SET($user_id, field_agent_ids)")->first();
            if (!$protect_request) {
                return $this->returnError('Request not found');
            }

            if ($protect_request->status != 'asign-protect') {
                return $this->returnError('Please reload the page and try again');
            }

            $validator['artwork_id'] = $protect_request->artwork_id;

            if (!isset($validator['is_signature']))
                $validator['is_signature'] = null;
            if (!isset($validator['is_inscription']))
                $validator['is_inscription'] = null;

            if ($validator['is_signature'] == 0 || $validator['is_signature'] == null)
                $validator['signature'] = null;
            if ($validator['is_inscription'] == 0 || $validator['is_inscription'] == null) {
                $validator['recto_inscription'] = null;
                $validator['verso_inscription'] = null;
                $validator['base_inscription'] = null;
            }

            if (isset($validator['cover_image'])) {
                $base_name = pathinfo($validator['cover_image'])['basename'];
                Storage::disk('s3')->move('artworks/temp/' . $base_name, 'artworks/' . $base_name);
                $validator['cover_image'] = 'artworks/' . $base_name;
            }

            $validator['verifier_id'] = $user_id;
            $message = 'Component added successfully';
            if ($component_id) {
                $message = 'Component updated successfully';
                $artworkComponent = ArtworkComponent::find($component_id);
                if (!$artworkComponent) return $this->returnError('Component not found');
            } else {
                $artworkComponent = new ArtworkComponent();
                $validator['asign_no'] = $this->asignCode($validator['artwork_id']);
            }

            if (isset($validator['technique_used'])) {
                $validator['technique_used'] = implode(',', $validator['technique_used']);
            }
            if (isset($validator['mediums'])) {
                $validator['mediums'] = implode(',', $validator['mediums']);
            }
            if (isset($validator['surface'])) {
                $validator['surface'] = implode(',', $validator['surface']);
            }


            $artworkComponent->fill($validator);
            $artworkComponent->save();

            $data = ['components' => [$artworkComponent]];
            $key = ArtworkComponent::where('artwork_id', $protect_request->artwork_id)->count() - 1;
            $result = [
                'id' => $artworkComponent->id,
                'data' => view('pages.protect_request.components.components', compact('data', 'key'))->render()
            ];
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        return $this->returnSuccess($result, $message);
    }


    private function asignCode($artwork_id)
    {
        $artwork = Artwork::where('id', $artwork_id)->first();
        $prefix = $artwork->asign_no;
        $max_code = ArtworkComponent::where('asign_no', 'LIKE', '%' . $prefix . '%')->max('asign_no');
        if ($max_code) {
            $max_code = (int)substr($max_code, 8);
            $max_code = $max_code + 1;
            if (strlen($max_code) < 4)
                $max_code = str_pad($max_code, 4, '0', STR_PAD_LEFT);
        } else {
            $max_code = '0001';
        }
        return $prefix . '-' . $max_code;
    }

    public function message(Request $request)
    {
        $id = $request->route('id');

        try {
            $validator = $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            $protect_request = ArtworkProtectRequest::find($id);
            if (!$protect_request) return $this->returnError('Request not found');

            $activityLog = $this->saveActivityLog($id, '', $request->input('message'));

            $result = $this->activityDataView($activityLog);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        return $this->returnSuccess($result, 'Message added successfully');
    }

    public function approve(Request $request)
    {
        $id = $request->route('id');
        try {
            $protect_request = ArtworkProtectRequest::find($id);
            if (!$protect_request) return $this->returnError('Request not found');
            $result = $this->approveStatus($protect_request);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess($result, 'Rejection override successfully');
    }

    public function rejection(Request $request)
    {
        $id = $request->route('id');
        try {
            $validator = $request->validate([
                'reject_reason_id' => 'required|integer',
                'reject_reason_message' => 'required|string|max:1000',
                'reviewer_id' => 'required|integer|exists:users,id',
            ]);

            $user_id = auth()->user()->id;

            $protect_request = ArtworkProtectRequest::find($id);
            if (!$protect_request) return $this->returnError('Request not found');

            $old_status = $protect_request->status;
            $old_status_arr = ArtworkProtectRequest::STATUS[$old_status] ?? null;

            $review = $old_status . '-review';

            if (!isset(ArtworkProtectRequest::STATUS[$review]))
                return $this->returnError('Request already ' . $old_status_arr['label']);

            $protect_request->status = $review;

            $column = $old_status_arr['role'] . '_ids';
            if (!in_array($user_id, explode(',', $protect_request->$column)))
                return $this->returnError('You are not authorized to reject this request');


            $protect_request->reject_reason_id = $request->input('reject_reason_id');
            $protect_request->reject_reason_message = $request->input('reject_reason_message');
            $protect_request->reviewer_id = $request->input('reviewer_id');
            $protect_request->save();

            $title = "Rejected " . $old_status_arr['label'];
            $activityLog = $this->saveActivityLog($id, $title, $request->input('reject_reason_message'), $old_status_arr['id']);

            $result = [
                'header' => $this->headerDataView($protect_request),
                'activity' => $this->activityDataView($activityLog),
            ];

            //           $customer_email = $protect_request->customer->email;
            //           $customer_email = 'sahayaanishjb@gmail.com';
            //            Mail::to($customer_email)->send(new Reject());


        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess($result, 'Rejection reason added successfully');
    }

    public function rejectionApprove(Request $request)
    {
        $id = $request->route('id');
        try {
            $validator = $request->validate([
                'reject_approve_message' => 'required|string|max:5000'
            ]);
            $user_id = auth()->user()->id;

            $protect_request = ArtworkProtectRequest::where(['id' => $id, 'reviewer_id' => $user_id])->first();

            if (!$protect_request) return $this->returnError('You are not authorized to approve this request');

            $old_status = $protect_request->status;
            $old_status_arr = ArtworkProtectRequest::STATUS[$old_status] ?? null;
            if ($old_status_arr['label'] != 'Review')
                return $this->returnError('Request already ' . $old_status_arr['label']);

            $protect_request->status = 'rejected';
            $protect_request->reject_approve_message = $request->input('reject_approve_message');


            $protect_request->save();

            $status = str_replace("-review", "", $old_status_arr['id']);
            $prev_status_arr = ArtworkProtectRequest::STATUS[$status] ?? null;

            $title = "Approved Rejection of " . $prev_status_arr['label'];
            $activityLog = $this->saveActivityLog($id, $title, null, $prev_status_arr['id']);

            $result = [
                'header' => $this->headerDataView($protect_request),
                'activity' => $this->activityDataView($activityLog),
            ];
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess($result, 'Rejection reason added successfully');
    }

    public function rejectionOverride(Request $request)
    {
        $id = $request->route('id');
        try {

            $user_id = auth()->user()->id;

            $protect_request = ArtworkProtectRequest::where(['id' => $id, 'reviewer_id' => $user_id])->first();
            if (!$protect_request) return $this->returnError('You are not authorized to override this request');

            $old_status = $protect_request->status;
            $old_status_arr = ArtworkProtectRequest::STATUS[$old_status] ?? null;

            if ($old_status_arr['label'] != 'Review')
                return $this->returnError('Request already ' . $old_status_arr['label']);

            if ($old_status_arr['next'] == "approved")
                $protect_request->approved_at = now();

            $protect_request->status = $old_status_arr['next'];

            $status = str_replace("-review", "", $old_status_arr['id']);

            $status_timeline = $protect_request->status_timeline;
            if (!isset($status_timeline[$status]))
                $status_timeline[$status] = [];
            $status_timeline[$status] = ['approve' => true, 'date' => now(), 'user_id' => auth()->user()->id];
            $protect_request->status_timeline = $status_timeline;

            $protect_request->save();


            $prev_status_arr = ArtworkProtectRequest::STATUS[$status] ?? null;
            $title = "Approved " . $prev_status_arr['label'];
            $activityLog = $this->saveActivityLog($id, $title, null, $prev_status_arr['id']);

            $result = [
                'header' => $this->headerDataView($protect_request),
                'activity' => $this->activityDataView($activityLog),
            ];
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }

        return $this->returnSuccess($result, 'Rejection override successfully');
    }


    public function fileUpload(Request $request)
    {
        try {
            $validator = $request->validate([
                'file' => 'required|file|max:51200|mimes:jpeg,jpg,png,webp',
            ]);
            $name = UtilsHelper::saveImage('file', 'artworks/temp');
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        return $this->returnSuccess([
            'name' => $name,
            'url' => UtilsHelper::storagePath($name)
        ], 'File uploaded successfully');
    }


    public function fileUploadCrop(Request $request)
    {
        try {
            $id = $request->route('id');
            $validator = $request->validate([
                'file' => 'required|file|max:51200|mimes:jpeg,jpg,png,webp',
            ]);
            $name = UtilsHelper::saveImage('file', 'artworks');
            $protect_request = ArtworkProtectRequest::find($id);
            if (!$protect_request) return $this->returnError('Request not found');

            ArtworkMedia::create([
                'artwork_id' => $protect_request->artwork_id,
                'value' => $name,
                'tag' => 'additional-image',
            ]);
            return $this->returnSuccess([
                'name' => $name,
                'url' => UtilsHelper::storagePath($name)
            ], 'File uploaded successfully');
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }


    private function headerDataView($protect_request)
    {
        $inspection = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $protect_request->id)->first();
        $data = [
            'id' => $protect_request->id,
            'request_id' => $protect_request->request_id,
            'customer_id' => $protect_request->customer_id,
            'status' => ArtworkProtectRequest::STATUS[$protect_request->status] ?? null,
            'authenticator_ids' => $protect_request->authenticator_ids,
            'conservator_ids' => $protect_request->conservator_ids,
            'field_agent_ids' => $protect_request->field_agent_ids,
            'service_provider_ids' => $protect_request->service_provider_ids,
            'reviewer_id' => $protect_request->reviewer_id,
            'inspection_date' => $protect_request->inspection_date,
            'inspection_time' => $protect_request->inspection_time,
            'visit_date' => $protect_request->visit_date,
            'visit_time' => $protect_request->visit_time,
            'verify_status' => $protect_request->verify_status ?? null,
            'inspection' => $inspection,
            'reference_img_url' => $protect_request->reference_img_url
        ];

        return view('pages.protect_request.components.header', compact('data'))->render();
    }

    private function activityDataView($activity)
    {
        $data = [
            'activities' => [$activity],
        ];
        return view('pages.protect_request.components.activity', compact('data'))->render();
    }

    private function saveActivityLog($request_id, $title, $message = '', $status = null)
    {
        $activityLog = new ArtworkProtectActivityLog();
        $activityLog->request_id = $request_id;
        $activityLog->user_id = auth()->user()->id;
        $activityLog->title = $title;
        $activityLog->tag = 'user';
        $activityLog->message = $message;
        $activityLog->user_agent = request()->header('User-Agent');
        $activityLog->ip_address = request()->ip();
        $activityLog->status = $status;
        $activityLog->save();

        return $activityLog;
    }


    private function asignProtectApproveVerify($protect, $status)
    {
        $all_verified = true;

        if (!isset($status['images'])) return false;
        if ($status['images'][0]['verify'] == 'false') return false;

        //        if (!isset($status['object-identification'])) return false;
        //        if ($status['object-identification'][0]['verify'] == 'false') return false;

        if (!isset($status['about'])) return false;
        if ($status['about'][0]['verify'] == 'false') return false;

        if (!isset($status['medium'])) return false;
        if ($status['medium'][0]['verify'] == 'false') return false;

        if (!isset($status['signature'])) return false;
        if ($status['signature'][0]['verify'] == 'false') return false;

        if (!isset($status['characteristics'])) return false;
        if ($status['characteristics'][0]['verify'] == 'false') return false;

        if (!isset($status['components'][0])) return false;
        if ($status['components'][0]['verify'] == 'false') return false;

        if (!isset($status['locations'][0])) return false;
        if ($status['locations'][0]['verify'] == 'false') return false;

        $location = ArtworkProtectLocation::where('request_id', $protect->id)->get(['id']);
        if ($location->count() > 0) {
            foreach ($location as $key => $value) {
                if (!isset($status['locations'][$value->id])) {
                    $all_verified = false;
                    break;
                } else {
                    if ($status['locations'][$value->id]['verify'] == 'false') {
                        $all_verified = false;
                        break;
                    }
                }
            }
        }

        $component = ArtworkComponent::where('artwork_id', $protect->artwork_id)->get(['id']);
        if ($component->count() > 0) {
            foreach ($component as $key => $value) {
                if (!isset($status['components'][$value->id])) {
                    $all_verified = false;
                    break;
                } else {
                    if ($status['components'][$value->id]['verify'] == 'false') {
                        $all_verified = false;
                        break;
                    }
                }
            }
        }

        return $all_verified;
    }

    public function inspectionInputs(Request $request, $artprotectinspection)
    {
        $artprotectinspection->is_object_match_imageupload = $request->input('objectMatchImageUpload');
        $artprotectinspection->object_match_imageupload_reason = $request->input('objectMatchImageUploadReason');
        $artprotectinspection->object_match_imageupload_reason_notes = $request->input('objectMatchImageUploadReasonNotes');
        $artprotectinspection->object_condition = $request->input('objectCondition');
        $artprotectinspection->is_object_noticeable_damages = $request->input('noticeableDamages');
        $artprotectinspection->object_noticeable_damage_reason = $request->input('objectNoticeableDamageReason');
        $artprotectinspection->object_noticeable_damage_reason_notes = $request->input('objectNoticeableDamageReasonNotes');
        $artprotectinspection->is_object_asignprotect_condition = $request->input('asignProtectCondition');
        $artprotectinspection->object_asignprotect_condition_reason = $request->input('asignProtectConditionReason');
        $artprotectinspection->object_asignprotect_condition_reason_notes = $request->input('asignProtectConditionReasonNotes');
        $artprotectinspection->is_object_surface_suitable = $request->input('surfaceSuitable');
        $artprotectinspection->object_surface_type = $request->input('surfaceLabelApplied');
        $artprotectinspection->object_material_frame = $request->input('materialFrame');
        $artprotectinspection->object_material_frame_notes = $request->input('materialFrameNotes');
        $artprotectinspection->object_material_objectstand = $request->input('materialStand');
        $artprotectinspection->object_material_objectstand_notes = $request->input('materialStandNotes');
        $artprotectinspection->object_material_stretcher = $request->input('materialStretcher');
        $artprotectinspection->object_material_stretcher_notes = $request->input('materialStretcherNotes');
        $artprotectinspection->object_surface_suitable_reason = $request->input('objectSurfaceSuitableReason');
        $artprotectinspection->object_surface_suitable_reason_notes = $request->input('objectSurfaceSuitableReasonNotes');
        $artprotectinspection->object_additional_notes = $request->input('objectAdditionalNotes');
        $artprotectinspection->object_additional_reason_notes = $request->input('objectAdditionalReasonNotes');
        $artprotectinspection->is_site_adequatephysical_taskcomplete = $request->input('adequatePhysicalSpace');
        $artprotectinspection->site_adequatephysical_taskcomplete_reason = $request->input('adequatePhysicalSpaceReason');
        $artprotectinspection->site_adequatephysical_taskcomplete_reason_notes = $request->input('adequatePhysicalSpaceReasonNotes');
        $artprotectinspection->is_site_adequatephysical_alternativespace = $request->input('adequatePhysicalAlternativeSpace');
        $artprotectinspection->site_adequatephysical_alternativespace_notes = $request->input('adequatePhysicalAlternativespaceNotes');
        $artprotectinspection->is_site_smoothworkflow = $request->input('smoothWorkflow');
        $artprotectinspection->site_smoothworkflow_reason = $request->input('smoothWorkflowReason');
        $artprotectinspection->site_smoothworkflow_reason_notes = $request->input('smoothWorkflowReasonNotes');
        $artprotectinspection->site_entry_points = $request->input('entryPoints');
        $artprotectinspection->site_exit_points = $request->input('exitPoints');
        $artprotectinspection->is_site_lighting_adequate = $request->input('lightingAdequate');
        $artprotectinspection->site_lighting_adequate_reason = $request->input('lightingAdequateReason');
        $artprotectinspection->site_lighting_adequate_reason_notes = $request->input('lightingAdequateReasonNotes');
        $artprotectinspection->is_site_lighting_adequate_alternativespace = $request->input('lightingAdequateAlternativeSpace');
        $artprotectinspection->site_lighting_adequate_alternativespace_notes = $request->input('lightingAdequateAlternativespaceNotes');
        $artprotectinspection->site_surrounding_workspace_reason = $request->input('surroundingWorkSpaceReason');
        $artprotectinspection->site_surrounding_workspace_reason_notes = $request->input('surroundingWorkSpaceReasonNotes');
        $artprotectinspection->is_site_surrounding_workspace_alternativespace = $request->input('surroundingWorkSpaceAlternativeSpace');
        $artprotectinspection->site_surrounding_workspace_alternativespace_notes = $request->input('surroundingWorkSpaceAlternativespaceNotes');
        $artprotectinspection->is_site_surrounding_workspace = $request->input('surroundingWorkSpace');
        $artprotectinspection->is_site_safety_protocols = $request->input('safetyProtocols');
        $artprotectinspection->site_emergency_exit = $request->input('emergencyExit');
        $artprotectinspection->site_security_requirements = $request->input('securityRequirements');
        $artprotectinspection->site_observation_security = $request->input('observationSecurity');
        $artprotectinspection->is_site_washroom_available = $request->input('washroomAvailable');
        $artprotectinspection->site_washroom_located_accessed = $request->input('locatedAndAccessed');
        $artprotectinspection->site_washroom_located_nearest = $request->input('nearestWashroom');
        $artprotectinspection->is_site_network_coverage = $request->input('networkCoverage');
        $artprotectinspection->site_alternate_available_network = $request->input('alternateAvailableNetwork');
        $artprotectinspection->site_additional_notes = $request->input('siteAdditionalNotes');
        $artprotectinspection->is_site_condition_checked = $request->input('applySiteCondition');

        return $artprotectinspection;
    }

    public function saveInspections(Request $request)
    {
        $id = $request->id;
        $protect_request_inspection = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $id);

        try {
            if ($protect_request_inspection->exists()) {
                $artprotectinspection = $protect_request_inspection->first();
                $artprotectinspection = $this->inspectionInputs($request, $artprotectinspection);
                $artprotectinspection->update();
                $protect_request = ArtworkProtectRequest::where(['id' => $id])->first();
                return $this->returnSuccess(['header' => $this->headerDataView($protect_request)], "Inspection tab details updated successfully");
            }

            if ($protect_request_inspection->doesntExist()) {
                $artprotectinspection = new ArtworkProtectRequestsInspection;
                $artprotectinspection = $this->inspectionInputs($request, $artprotectinspection);
                $artprotectinspection->artwork_protect_requests_id = $id;
                $artprotectinspection->save();
                $protect_request = ArtworkProtectRequest::where(['id' => $id])->first();
                return $this->returnSuccess(['header' => $this->headerDataView($protect_request)], "Inspection tab details created successfully");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function inspectionFileUpload(Request $request)
    {
        $id = $request->id;
        $protect_request_inspection = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $id);
        try {
            if ($protect_request_inspection->exists()) {
                $artprotectinspection = $protect_request_inspection->first();
                $image_name_arr = !empty($artprotectinspection->object_label_images) ? json_decode($artprotectinspection->object_label_images, true) : [];

                $image = $request->get('object_label_image');
                $file = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
                $random = Str::random(10);
                //$image_name = 'inspection_'.$id.'_A3n5n30gRg.png';
                $image_name = 'inspection_' . $id . '_' . $random . '.png';
                //Storage::disk('local')->put($image_name, $file);

                if (Storage::disk('s3')->exists('artworks/inspection/' . $image_name)) {
                    Storage::disk('s3')->delete('artworks/inspection/' . $image_name);
                } else {
                    Storage::disk('s3')->put('artworks/inspection/' . $image_name, $file);
                }

                $image_name_arr[] = $image_name;
                $artprotectinspection->object_label_images = json_encode($image_name_arr);

                if ($artprotectinspection->update()) {
                    return $this->returnSuccess([
                        'name' => $image_name,
                        'full_url' => UtilsHelper::storagePath('artworks/inspection/' . $image_name)
                        //'full_url' => 'http://localhost/asignartnew/storage/app/'.$image_name
                    ], 'File uploaded successfully');
                }
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function inspectionDamageFileUpload(Request $request)
    {
        $id = $request->id;
        $protect_request_inspection = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $id);
        try {
            if ($protect_request_inspection->exists()) {
                $artprotectinspection = $protect_request_inspection->first();
                $image_name_arr = !empty($artprotectinspection->object_noticeable_damage_reason_images) ? json_decode($artprotectinspection->object_noticeable_damage_reason_images, true) : [];

                $request->validate([
                    'image-damage' => 'required|mimes:jpg,jpeg,png,gif,svg'
                ]);

                $file = request()->file('image-damage');
                $random = Str::random(10);
                //$image_name = 'inspection_damage_'.$id.'_A3n5n30gRg.png';
                $image_name = 'inspection_damage_' . $id . '_' . $random . '.png';
                //Storage::disk('local')->put($image_name, file_get_contents($file));

                if (Storage::disk('s3')->exists('artworks/inspection/' . $image_name)) {
                    Storage::disk('s3')->delete('artworks/inspection/' . $image_name);
                } else {
                    Storage::disk('s3')->put('artworks/inspection/' . $image_name, file_get_contents($file));
                }

                $image_name_arr[] = $image_name;
                $artprotectinspection->object_noticeable_damage_reason_images = json_encode($image_name_arr);

                if ($artprotectinspection->update()) {
                    return $this->returnSuccess([
                        'name' => $image_name,
                        'full_url' => UtilsHelper::storagePath('artworks/inspection/' . $image_name)
                        //'full_url' => 'http://localhost/asignartnew/storage/app/'.$image_name
                    ], 'File uploaded successfully');
                }
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function inspectionObjectFileUpload(Request $request)
    {
        $id = $request->id;
        $protect_request_inspection = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $id);
        try {
            if ($protect_request_inspection->exists()) {
                $artprotectinspection = $protect_request_inspection->first();
                $image_name_arr = !empty($artprotectinspection->object_surface_suitable_reason_images) ? json_decode($artprotectinspection->object_surface_suitable_reason_images, true) : [];

                $request->validate([
                    'image-object' => 'required|mimes:jpg,jpeg,png,gif,svg'
                ]);

                $file = request()->file('image-object');
                $random = Str::random(10);
                //$image_name = 'inspection_object_'.$id.'_A3n5n30gRg.png';
                $image_name = 'inspection_object_' . $id . '_' . $random . '.png';
                //Storage::disk('local')->put($image_name, file_get_contents($file));

                if (Storage::disk('s3')->exists('artworks/inspection/' . $image_name)) {
                    Storage::disk('s3')->delete('artworks/inspection/' . $image_name);
                } else {
                    Storage::disk('s3')->put('artworks/inspection/' . $image_name, file_get_contents($file));
                }

                $image_name_arr[] = $image_name;
                $artprotectinspection->object_surface_suitable_reason_images = json_encode($image_name_arr);

                if ($artprotectinspection->update()) {
                    return $this->returnSuccess([
                        'name' => $image_name,
                        'full_url' => UtilsHelper::storagePath('artworks/inspection/' . $image_name)
                        //'full_url' => 'http://localhost/asignartnew/storage/app/'.$image_name
                    ], 'File uploaded successfully');
                }
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function inspectionRemoveFileUploads(Request $request)
    {
        $id = $request->id;
        $protect_request_inspection = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $id);
        try {
            if ($protect_request_inspection->exists()) {
                $artprotectinspection = $protect_request_inspection->first();

                if ($request->get('data_image_type') == 'object_label_images') {
                    $image_name_arr = !empty($artprotectinspection->object_label_images) ? json_decode($artprotectinspection->object_label_images, true) : [];

                    if (!empty($image_name_arr)) {
                        foreach ($image_name_arr as $value) {
                            if (Storage::disk('s3')->exists('artworks/inspection/' . $value)) {
                                Storage::disk('s3')->delete('artworks/inspection/' . $value);
                            }
                        }

                        $artprotectinspection->object_label_images = [];

                        if ($artprotectinspection->update()) {
                            return $this->returnSuccess([], 'Files removed successfully');
                        }
                    }
                } elseif ($request->get('data_image_type') == 'damage_images') {
                    $image_name_arr = !empty($artprotectinspection->object_noticeable_damage_reason_images) ? json_decode($artprotectinspection->object_noticeable_damage_reason_images, true) : [];

                    if (!empty($image_name_arr)) {
                        foreach ($image_name_arr as $value) {
                            if (Storage::disk('s3')->exists('artworks/inspection/' . $value)) {
                                Storage::disk('s3')->delete('artworks/inspection/' . $value);
                            }
                        }

                        $artprotectinspection->object_noticeable_damage_reason_images = [];

                        if ($artprotectinspection->update()) {
                            return $this->returnSuccess([], 'Files removed successfully');
                        }
                    }
                } elseif ($request->get('data_image_type') == 'object_images') {
                    $image_name_arr = !empty($artprotectinspection->object_surface_suitable_reason_images) ? json_decode($artprotectinspection->object_surface_suitable_reason_images, true) : [];

                    if (!empty($image_name_arr)) {
                        foreach ($image_name_arr as $value) {
                            if (Storage::disk('s3')->exists('artworks/inspection/' . $value)) {
                                Storage::disk('s3')->delete('artworks/inspection/' . $value);
                            }
                        }

                        $artprotectinspection->object_surface_suitable_reason_images = [];

                        if ($artprotectinspection->update()) {
                            return $this->returnSuccess([], 'Files removed successfully');
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function inspectionSiteConditionRequestData($protect_request, $city, $status = 'asign-protect')
    {
        $data = DB::table('artwork_protect_requests as apr')
            ->leftJoin('artwork_protect_requests_inspections as apri', 'apri.artwork_protect_requests_id', '=', 'apr.id')
            ->join('artworks as a', 'a.id', '=', 'apr.artwork_id')
            ->leftJoin('customers as c', 'c.id', '=', 'a.artist_id')
            ->where('a.city', '=', $city)
            ->where('apr.status', '=', $status)
            ->where('apr.request_id', '!=', $protect_request->request_id)
            /* ->where('apr.id', '=', $request_id)->get(); */
            ->where('apr.customer_id', '=', $protect_request->customer_id)->select('apr.request_id', 'apr.id', 'apr.inspection_date')->get();

        return [
            'request_count' => $data->count(),
            'request_data' => $data
        ];
    }

    public function inspectionSiteconditionInputsOverride($artprotectinspection_exist, $artprotectinspection_new)
    {
        $artprotectinspection_exist->is_site_adequatephysical_taskcomplete = isset($artprotectinspection_new->is_site_adequatephysical_taskcomplete) ? $artprotectinspection_new->is_site_adequatephysical_taskcomplete : NULL;
        $artprotectinspection_exist->site_adequatephysical_taskcomplete_reason = !empty($artprotectinspection_new->site_adequatephysical_taskcomplete_reason) ? $artprotectinspection_new->site_adequatephysical_taskcomplete_reason : NULL;
        $artprotectinspection_exist->site_adequatephysical_taskcomplete_reason_notes = !empty($artprotectinspection_new->site_adequatephysical_taskcomplete_reason_notes) ? $artprotectinspection_new->site_adequatephysical_taskcomplete_reason_notes : NULL;
        $artprotectinspection_exist->is_site_adequatephysical_alternativespace = isset($artprotectinspection_new->is_site_adequatephysical_alternativespace) ? $artprotectinspection_new->is_site_adequatephysical_alternativespace : NULL;
        $artprotectinspection_exist->site_adequatephysical_alternativespace_notes = !empty($artprotectinspection_new->site_adequatephysical_alternativespace_notes) ? $artprotectinspection_new->site_adequatephysical_alternativespace_notes : NULL;
        $artprotectinspection_exist->is_site_smoothworkflow = isset($artprotectinspection_new->is_site_smoothworkflow) ? $artprotectinspection_new->is_site_smoothworkflow : NULL;
        $artprotectinspection_exist->site_smoothworkflow_reason = !empty($artprotectinspection_new->site_smoothworkflow_reason) ? $artprotectinspection_new->site_smoothworkflow_reason : NULL;
        $artprotectinspection_exist->site_smoothworkflow_reason_notes = !empty($artprotectinspection_new->site_smoothworkflow_reason_notes) ? $artprotectinspection_new->site_smoothworkflow_reason_notes : NULL;
        $artprotectinspection_exist->site_entry_points = !empty($artprotectinspection_new->site_entry_points) ? $artprotectinspection_new->site_entry_points : NULL;
        $artprotectinspection_exist->site_exit_points = !empty($artprotectinspection_new->site_exit_points) ? $artprotectinspection_new->site_exit_points : NULL;
        $artprotectinspection_exist->is_site_lighting_adequate = isset($artprotectinspection_new->is_site_lighting_adequate) ? $artprotectinspection_new->is_site_lighting_adequate : NULL;
        $artprotectinspection_exist->site_lighting_adequate_reason = !empty($artprotectinspection_new->site_lighting_adequate_reason) ? $artprotectinspection_new->site_lighting_adequate_reason : NULL;
        $artprotectinspection_exist->site_lighting_adequate_reason_notes = !empty($artprotectinspection_new->site_lighting_adequate_reason_notes) ? $artprotectinspection_new->site_lighting_adequate_reason_notes : NULL;
        $artprotectinspection_exist->is_site_lighting_adequate_alternativespace = isset($artprotectinspection_new->is_site_lighting_adequate_alternativespace) ? $artprotectinspection_new->is_site_lighting_adequate_alternativespace : NULL;
        $artprotectinspection_exist->site_lighting_adequate_alternativespace_notes = !empty($artprotectinspection_new->site_lighting_adequate_alternativespace_notes) ? $artprotectinspection_new->site_lighting_adequate_alternativespace_notes : NULL;
        $artprotectinspection_exist->site_surrounding_workspace_reason = !empty($artprotectinspection_new->site_surrounding_workspace_reason) ? $artprotectinspection_new->site_surrounding_workspace_reason : NULL;
        $artprotectinspection_exist->site_surrounding_workspace_reason_notes = !empty($artprotectinspection_new->site_surrounding_workspace_reason_notes) ? $artprotectinspection_new->site_surrounding_workspace_reason_notes : NULL;
        $artprotectinspection_exist->is_site_surrounding_workspace_alternativespace = !empty($artprotectinspection_new->is_site_surrounding_workspace_alternativespace) ? $artprotectinspection_new->is_site_surrounding_workspace_alternativespace : NULL;
        $artprotectinspection_exist->site_surrounding_workspace_alternativespace_notes = !empty($artprotectinspection_new->site_surrounding_workspace_alternativespace_notes) ? $artprotectinspection_new->site_surrounding_workspace_alternativespace_notes : NULL;
        $artprotectinspection_exist->is_site_surrounding_workspace = isset($artprotectinspection_new->is_site_surrounding_workspace) ? $artprotectinspection_new->is_site_surrounding_workspace : NULL;
        $artprotectinspection_exist->is_site_safety_protocols = isset($artprotectinspection_new->is_site_safety_protocols) ? $artprotectinspection_new->is_site_safety_protocols : NULL;
        $artprotectinspection_exist->site_emergency_exit = !empty($artprotectinspection_new->site_emergency_exit) ? $artprotectinspection_new->site_emergency_exit : NULL;
        $artprotectinspection_exist->site_security_requirements = !empty($artprotectinspection_new->site_security_requirements) ? $artprotectinspection_new->site_security_requirements : NULL;
        $artprotectinspection_exist->site_observation_security = !empty($artprotectinspection_new->site_observation_security) ? $artprotectinspection_new->site_observation_security : NULL;
        $artprotectinspection_exist->is_site_washroom_available = isset($artprotectinspection_new->is_site_washroom_available) ? $artprotectinspection_new->is_site_washroom_available : NULL;
        $artprotectinspection_exist->site_washroom_located_accessed = !empty($artprotectinspection_new->site_washroom_located_accessed) ? $artprotectinspection_new->site_washroom_located_accessed : NULL;
        $artprotectinspection_exist->site_washroom_located_nearest = !empty($artprotectinspection_new->site_washroom_located_nearest) ? $artprotectinspection_new->site_washroom_located_nearest : NULL;
        $artprotectinspection_exist->is_site_network_coverage = isset($artprotectinspection_new->is_site_network_coverage) ? $artprotectinspection_new->is_site_network_coverage : NULL;
        $artprotectinspection_exist->site_alternate_available_network = !empty($artprotectinspection_new->site_alternate_available_network) ? $artprotectinspection_new->site_alternate_available_network : NULL;
        $artprotectinspection_exist->site_additional_notes = !empty($artprotectinspection_new->site_additional_notes) ? $artprotectinspection_new->site_additional_notes : NULL;
        //$artprotectinspection_exist->is_site_condition_checked = isset($artprotectinspection_new->is_site_condition_checked) ? $artprotectinspection_new->is_site_condition_checked : NULL;

        return $artprotectinspection_exist;
    }

    public function inspectionSiteconditionChecklistRequestAll(Request $request)
    {
        $protect_request = ArtworkProtectRequest::where('id', $request->id);
        $protect_request = $protect_request->first();
        $artwork = $protect_request->artwork;

        if ($request->get('sitecondition_request_type') == 1) {
            $data = $this->inspectionSiteConditionRequestData($protect_request, $artwork->city, 'inspection');
            $result = false;
            $protect_request_inspection_exist = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $request->id);
            foreach ($data['request_data'] as $value) {
                $protect_request_inspection_new = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $value->id);

                if ($protect_request_inspection_exist->exists()) {
                    $artprotectinspection_exist = $protect_request_inspection_exist->first();
                    $artprotectinspection_new = $protect_request_inspection_new->first();

                    if (!count($protect_request_inspection_new->get())) {
                        $artprotectinspection = new ArtworkProtectRequestsInspection;
                        $artprotectinspection->is_object_match_imageupload = NULL;
                        $artprotectinspection->artwork_protect_requests_id = $value->id;
                        if ($artprotectinspection->save()) {
                            $protect_request_inspection_exist = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $request->id);
                            $protect_request_inspection_new = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $value->id);

                            $artprotectinspection_exist = $protect_request_inspection_exist->first();
                            $artprotectinspection_new = $protect_request_inspection_new->first();

                            $artprotectinspection_new = $this->inspectionSiteconditionInputsOverride($artprotectinspection_new, $artprotectinspection_exist);

                            if ($artprotectinspection_new->update()) {
                                $result = true;
                            }
                        }
                    } else {
                        $artprotectinspection_new = $this->inspectionSiteconditionInputsOverride($artprotectinspection_new, $artprotectinspection_exist);

                        if ($artprotectinspection_new->update()) {
                            $result = true;
                        }
                    }
                }
            }

            if ($result) {
                return $this->returnSuccess("Site Condition changes applied to all open requests at same location");
            }
        } else {
            //return false;
        }
    }


    public function inspectionSiteconditionChecklistRequest(Request $request)
    {
        $protect_request_inspection_exist = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $request->id);
        $protect_request_inspection_new = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $request->get('sitecondition_request_id'));

        try {
            if ($protect_request_inspection_exist->exists()) {
                if (!count($protect_request_inspection_new->get())) {
                    $artprotectinspection = new ArtworkProtectRequestsInspection;
                    $artprotectinspection->is_object_match_imageupload = NULL;
                    $artprotectinspection->artwork_protect_requests_id = $request->get('sitecondition_request_id');
                    $artprotectinspection->save();
                } else {
                    $artprotectinspection_exist = $protect_request_inspection_exist->first();
                    $artprotectinspection_new = $protect_request_inspection_new->first();

                    $artprotectinspection_exist = $this->inspectionSiteconditionInputsOverride($artprotectinspection_exist, $artprotectinspection_new);

                    $artprotectinspection_exist->update();
                    return $this->returnSuccess($artprotectinspection_exist, "Site Condition Checklist details updated successfully");
                }
            } elseif ($protect_request_inspection_exist->doesntExist()) {
                $artprotectinspection = new ArtworkProtectRequestsInspection;
                $artprotectinspection->artwork_protect_requests_id = $request->id;
                $artprotectinspection->save();

                $protect_request_inspection_exist = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $request->id);
                $protect_request_inspection_new = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $request->get('sitecondition_request_id'));
                if (count($protect_request_inspection_new->get())) {
                    $artprotectinspection_exist = $protect_request_inspection_exist->first();
                    $artprotectinspection_new = $protect_request_inspection_new->first();

                    $artprotectinspection_exist = $this->inspectionSiteconditionInputsOverride($artprotectinspection_exist, $artprotectinspection_new);

                    $artprotectinspection_exist->update();
                    return $this->returnSuccess($artprotectinspection_exist, "Site Condition Checklist details updated successfully");
                }
            } else {
                return $this->returnError("Site Condition Checklist details update failed");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function provenanceAuthenticatorInputs(Request $request, $artprotectprovenance_authenticator)
    {
        $artprotectprovenance_authenticator->is_provenance_objective_verification = $request->input('provenanceObjectiveVerification');
        $artprotectprovenance_authenticator->is_provenance_art_upload = $request->input('provenanceArtUploadByOtherUser');
        $artprotectprovenance_authenticator->provenance_object_number = $request->input('provenanceObjectNumberOfObjectHidden');
        $artprotectprovenance_authenticator->is_provenance_confirm_object = $request->input('confirmIsObject');
        $artprotectprovenance_authenticator->provenance_reason = $request->input('provenanceReason');
        $artprotectprovenance_authenticator->provenance_additional_notes = $request->input('provenanceObjectAdditionalNotes');

        return $artprotectprovenance_authenticator;
    }

    public function saveProvenanceAuthenticator(Request $request)
    {
        $id = $request->id;
        $protect_request_provenance_authenticator = ArtworkProtectRequestsInspection::where('artwork_protect_requests_id', '=', $id);

        try {
            if ($protect_request_provenance_authenticator->exists()) {
                $artprotectprovenance_authenticator = $protect_request_provenance_authenticator->first();
                $artprotectprovenance_authenticator = $this->provenanceAuthenticatorInputs($request, $artprotectprovenance_authenticator);
                $artprotectprovenance_authenticator->update();
                $protect_request = ArtworkProtectRequest::where(['id' => $id])->first();
                return $this->returnSuccess(['header' => $this->headerDataView($protect_request)], "Provenance authenticator checklist details updated successfully");
            }

            if ($protect_request_provenance_authenticator->doesntExist() && !count($protect_request_provenance_authenticator->get())) {
                $artprotectprovenance_authenticator = new ArtworkProtectRequestsInspection;
                $artprotectprovenance_authenticator = $this->provenanceAuthenticatorInputs($request, $artprotectprovenance_authenticator);
                $artprotectprovenance_authenticator->artwork_protect_requests_id = $id;
                $artprotectprovenance_authenticator->save();
                $protect_request = ArtworkProtectRequest::where(['id' => $id])->first();
                return $this->returnSuccess(['header' => $this->headerDataView($protect_request)], "Provenance authenticator checklist details created successfully");
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function provenanceAsignObjectNumberCheck(Request $request)
    {
        $data = DB::table('artwork_protect_requests as apr')
            ->join('artworks as a', 'a.id', '=', 'apr.artwork_id')
            ->leftJoin('customers as c', 'c.id', '=', 'a.artist_id')
            ->where('a.asign_no', '=', $request->asign_no)
            ->where('apr.id', '=', $request->id)->select('a.asign_no', 'a.artist_id', 'a.unknown_artist', 'a.title', 'a.creation_year_from', 'c.full_name')->get();

        try {
            if (!count($data)) {
                return $this->returnSuccess('Asign Object Number does not Exist');
            } else {
                return $this->returnSuccess([
                    'link' => ($data[0]->artist_id ? $data[0]->full_name : $data[0]->unknown_artist) . '-' . $data[0]->title . ',' . $data[0]->creation_year_from,
                    'asign_no' => $data[0]->asign_no
                ], 'Asign Object Number Exist');
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    private function showScannerStep($request, $formtype = null, $new_step = null)
    {
        # ... Getting saved values for specific record
        $step = $request->current_step;

        $child_labels = is_array($request->child_labels) ? $request->child_labels : [];
        $lbl_count = count($child_labels);
        $child_step = $request->child_step;
        $child_direction = $request->child_direction;
        $title = "A";

        $data = [
            "object_img" => $request->object_img_url,
            "reference_img" => $request->reference_img_url,
            "percentage" => $request->matching_percentage,
            "status" => $request->status,
            'valid' => ''
        ];

        $type = $request->{$step};

        if ($step == 'inventory_label' || $step == 'auth_label') {
            if ($type) {
                $data['images'] = $type['images'];
                $data['envelope'] = $type['envelope'];
                $data['label'] = $type['label'];
                $data['valid'] = $type['label'] && $type['envelope'] ? 'valid' : '';
            }
        } else if ($step == 'inventory_label_child' || $step == 'auth_label_child') {

            # ... Create dynamic title for child labels
            if ($lbl_count > 0) {
                $ext = array_filter($child_labels, function ($var) use ($step) {
                    return ($var['type'] == $step);
                });
                $ext_count = count($ext);
                if ($ext_count >= 0 && $ext_count <= 25) {
                    $title = chr(65 + $ext_count);
                }
            }

            if ($step == 'inventory_label_child') {
                $title = 'Step 2' . $title . ': Apply Child Inventory Label';
            } else {
                $title = 'Step 3' . $title . ': Apply Child Authenticity Label';
            }

            # ... Assign data for a child form views
            if (isset($child_labels[$child_step])) {
                $current = $child_labels[$child_step];
                $title = $current['title'];
                $data['title'] = $current['title'];
                $data['images'] = $current['images'];
                $data['envelope'] = $current['envelope'];
                $data['label'] = $current['label'];
                $data['valid'] = $current['label'] && $current['envelope'] ? 'valid' : '';
            } else {
                $child_step = 'on_update';
            }
        }

        $label = [];
        $view_name = 'pages.protect_request.scanner.label';
        switch ($step) {
            case "preview":
                $view_name = 'pages.protect_request.scanner.preview';
                break;
            case "object_match":
                $view_name = 'pages.protect_request.scanner.object_match';
                break;
            case "edit_uploaded_image":
                $view_name = 'pages.protect_request.scanner.edit_uploaded_image';
                break;
            case "inventory_label":
                $label = [
                    "title" => "Step 2: Apply Inventory Label",
                    "label" => "Inventory",
                    "type" => "inventory_label",
                    "next" => "auth_label",
                    "prev" => "object_match"
                ];
                break;
            case "auth_label":
                $label = [
                    "title" => "Step 3: Apply Authenticity Label",
                    "label" => "Authenticity",
                    "type" => "auth_label",
                    "next" => "auth_label",
                    "prev" => "inventory_label"
                ];
                break;
            case "inventory_label_child":
                # ... Assign dynamic title to child_labels field
                $prev = $child_step == 0 || $child_step == 'on_update' ? "auth_label" : "auth_label_child";

                $label = [
                    "title" => $title,
                    "label" => "Child Inventory",
                    "type" => "inventory_label_child",
                    "next" => "auth_label_child",
                    "prev" => $prev
                ];
                break;
            case "auth_label_child":
                # ... Assign dynamic title to child_labels field
                $prev = $child_step == 0 ? "inventory_label" : "inventory_label_child";

                $label = [
                    "title" => $title,
                    "label" => "Child Authenticity",
                    "type" => "auth_label_child",
                    "next" => "auth_label",
                    "prev" => $prev
                ];
                break;
            default:
                $view_name = 'pages.protect_request.scanner.preview';
                break;
        }

        $label["child_step"] = $child_step;
        $label["child_count"] = $lbl_count;
        $label["current_index"] = $request->child_step;
        $label['formtype'] = "";

        if ($formtype == "standalone") {
            $data['title'] = "";
            $data['images'] = [];
            $data['envelope'] = "";
            $data['label'] = "";
            $data['valid'] = "";

            $label["next"] = "auth_label";
            $label["prev"] = "auth_label";
            $label["formtype"] = $formtype;
            $label["type"] = $new_step;

            $label["label"] = $new_step == 'inventory_label_child' ? 'Child Inventory' : 'Child Authenticity';
            $label["title"] = $new_step == 'inventory_label_child' ? 'Apply Inventory Child Label' : 'Apply Authenticity Child Label';
            $view_name = 'pages.protect_request.scanner.label';
        }

        return view($view_name, compact('data', 'label'))->render();
    }

    public function scannerAppStep(Request $request)
    {
        $input = $request->all();
        if ($input['request_id']) {
            try {
                $protect_request = ArtworkProtectRequest::where(['id' => $input['request_id']])->first();
                if ($protect_request) {

                    # ... Update reference image to Request table very first time only
                    if ($protect_request->reference_img_url == "" || $protect_request->reference_img_url == null) {
                        $pro_request = DB::table('artwork_protect_requests as awr')
                            ->leftJoin('artwork_media as awm', function ($q) {
                                $q->on('awm.artwork_id', '=', 'awr.artwork_id');
                                $q->where('awm.tag', '=', "featured");
                            })
                            ->where('awr.id', '=', $input['request_id'])
                            ->select('awr.customer_id', 'awr.object_img_url', 'awr.reference_img_url', 'awr.status', 'awr.matching_percentage', 'awr.inventory_label', 'awr.auth_label', 'awr.child_labels', 'awr.child_step', 'awr.child_direction', 'awm.value as object', 'awm.detail', 'awm.tag')
                            ->first();

                        $protect_request->reference_img_url = $pro_request->object;
                    }

                    # ... Current step save ...
                    if ($input['step']) {
                        if ($input['formtype'] != "standalone") {
                            $protect_request->current_step = $input['step'];
                        }
                        if ($input['step'] == "inventory_label_child" || $input['step'] == "auth_label_child") {
                            if ($input['direction'] === "prev") {
                                $protect_request->child_step = $protect_request->child_step <= 0 ? 0 : $protect_request->child_step - 1;
                            }
                        } else {
                            # ... not in inventory_label_child or auth_label_child set default options
                            $protect_request->child_step = null;
                            $protect_request->child_direction = null;
                            $input['direction'] = null;
                        }
                    }

                    $protect_request->save();

                    return $this->showScannerStep($protect_request, $input['formtype'], $input['step']);
                }
            } catch (\Throwable $e) {
                $e->getMessage();
                dd($e->getMessage());
            }
        }
    }

    public function scannerAppUpload(Request $request)
    {
        try {
            # ... Validations
            $req = $request->only('object', 'request_id', 'form_for', 'this_image', 'from_step', 'image_edit_type', 'img_type', 'temp_img_id');
            $rules = [
                'object' => 'required|file|max:51200|mimes:jpeg,jpg,png,webp',
            ];
            $messages = [
                'object.required' => 'Please upload an image',
                'object.file' => 'Please upload a valid image',
                'object.max' => 'Image size should be less than 1MB',
            ];
            $validator = Validator::make($req, $rules, $messages);
            if ($validator->fails()) {
                return $this->returnError($validator->messages());
            }

            $return_url = "";
            # ... Get formvalues & process with image upload
            $name = UtilsHelper::saveImage('object', 'artworks');
            $path = UtilsHelper::storagePath();
            if ($name) {
                $protect_request = ArtworkProtectRequest::where(['id' => $req['request_id']])->first();
                if ($protect_request) {
                    # ... Image matching
                    # ... $img1 Existing image
                    # ... $img2 Uploaded image
                    $img1 = config('app.image_url') . $protect_request->reference_img_url;
                    $img2 = config('app.image_url') . $name;
                    //$this_image = $req['this_image'];

                    if ($req['form_for'] === "object_match") {
                        $protect_request->current_step = "edit_uploaded_image";
                        $protect_request->matching_percentage = $this->imageMatch($img1, $img2);
                        $protect_request->object_img_url = $name;
                    } elseif ($req['form_for'] === "edit_uploaded_image") {
                        if (Storage::disk('s3')->exists($protect_request->object_img_url)) {
                            Storage::disk('s3')->delete($protect_request->object_img_url);
                            $protect_request->current_step = "object_match";
                            $protect_request->object_img_url = $name;
                            if ($req['image_edit_type'] == "rotate") {
                                $return_url = $req['image_edit_type'];
                            } else {
                                $protect_request->matching_percentage = $this->imageMatch($img1, $img2);
                            }
                        }
                    } elseif ($req['form_for'] === "edit_uploaded_image_alt") {

                        return [
                            'return_step' => 'only_crop_image',
                            'name' => config('app.image_url') . $name,
                            'old_image' => $req['this_image']
                        ];

                        $from_step = $req['from_step'];
                        $return_url = $from_step;

                        if ($req['img_type'] === "existing") {
                            #... Remove existing image & add new image to inventory_label or auth_label
                            $type = $protect_request->{$from_step};
                            if ($type) {
                                $old_images = $type['images'];
                                $image = str_replace($path, '', $req['this_image']);

                                if (($key = array_search($image, $old_images)) !== false) {
                                    $old_images[$key] = $name;
                                    $old_images = array_values($old_images);
                                }

                                $type['images'] = $old_images;
                                $protect_request->{$from_step} = $type;
                            }
                        } elseif ($req['img_type'] === "temp") {
                            # ... Add temp image to existing array then remove from temp table
                            $type = $protect_request->{$from_step};
                            if ($type) {
                                $old_images = $type['images'];
                                $image = str_replace($path, '', $req['this_image']);

                                if (($key = array_search($image, $old_images)) !== false) {
                                    $old_images[$key] = $name;
                                    $old_images = array_values($old_images);
                                }

                                $type['images'] = $old_images;
                                $protect_request->{$from_step} = $type;
                            } else {
                                $temp_img = TempImage::where('id', $req['temp_img_id'])->first();
                                if ($temp_img) {
                                    $temp_img->img_url = $name;
                                    $temp_img->status = "changed";
                                    $temp_img->save();
                                }
                            }
                        }
                    }
                    // else {
                    //     $protect_request->current_step = "edit_uploaded_image";
                    //     $protect_request->matching_percentage = $this->imageMatch($img1, $img2);
                    //     $protect_request->object_img_url = $name;
                    // }

                    $protect_request->save();
                }

                $data = [
                    "object_img" => $protect_request->object_img_url,
                    "reference_img" => $protect_request->reference_img_url,
                    "percentage" => $protect_request->matching_percentage
                ];

                $view = 'pages.protect_request.scanner.edit_uploaded_image';

                if ($req['form_for'] == "edit_uploaded_image") {
                    $view = 'pages.protect_request.scanner.object_match';
                }

                $result['scanner'] = view($view, compact('data'))->render();
                $result['header'] = $this->headerDataView($protect_request);
                $result['return_step'] = $return_url;
                return $result;
            }
        } catch (\Throwable $e) {
            //$e->getMessage();
            dd($e->getMessage());
        }
    }

    public function imageEditExtended(Request $request)
    {
        $path = UtilsHelper::storagePath();
        $request_id = $request->request_id;
        $from_step = $request->from_step;
        $extended_img = $request->extended_img; // If Saved image or/ temprory saved image id
        $img_type = $request->type; // Saved / Temp
        $data = [];

        try {
            $data['this_image'] = $request->old_url;
            $data['temp_img_id'] = "none";
            $data['from_step'] = $from_step;
            $data['img_type'] = $img_type;

            return view('pages.protect_request.scanner.edit_uploaded_image_alt', compact('data'))->render();


            $protect_request = ArtworkProtectRequest::where(['id' => $request_id])->first();
            if ($protect_request) {
                $data['from_step'] = $from_step;
                $data['img_type'] = $img_type;

                if ($img_type === "temp") {
                    $type = $protect_request->{$from_step};
                    if ($type) {
                        $old_images = $type['images'];
                        $temp_imgs = TempImage::where('request_id', $request_id)->get();
                        if (count($temp_imgs) > 0) {
                            foreach ($temp_imgs as $key => $img) {
                                $image = str_replace($path, '', $img->img_url);
                                if ($extended_img == $img->id) {
                                    $data['this_image'] = config('app.image_url') . $image;
                                    $data['temp_img_id'] = $extended_img;
                                    $img->status = "current";
                                }

                                $old_images[] = $image;
                                $img->delete();
                            }
                        }

                        $type['images'] = $old_images;
                        $protect_request->{$from_step} = $type;
                    } else {
                        // Need to fix this area
                        $temp_img = TempImage::where('id', $extended_img)->first();
                        if ($temp_img) {
                            $image = str_replace($path, '', $temp_img->img_url);
                            $data['this_image'] = config('app.image_url') . $image;
                            $data['temp_img_id'] = $extended_img;
                            $temp_img->status = "marked";
                            $temp_img->save();
                        }
                    }
                    $protect_request->save();
                } else {
                    // Existing image fetch for editing
                    if ($from_step == "auth_label" || $from_step == "inventory_label") {
                        $parent_label = $protect_request->{$from_step};
                        if ($parent_label) {
                            $images = array_values($parent_label['images']);
                            if (in_array($extended_img, $images)) {
                                $data['this_image'] = config('app.image_url') . $extended_img;
                                $data['temp_img_id'] = "none";
                            }
                        }
                    } else if ($from_step == "auth_label_child" || $from_step == "inventory_label_child") {
                        $child_labels = $protect_request->child_labels;
                        if ($child_labels) {
                            $lbl_imgs = [];
                            foreach ($child_labels as $lbl) {
                                if ($lbl['type'] === $from_step) {
                                    if ($lbl['images'] && count($lbl['images']) > 0) {
                                        $lbl_imgs = array_merge($lbl_imgs, $lbl['images']);
                                    }
                                }
                            }
                            if (in_array($extended_img, $lbl_imgs)) {
                                $data['this_image'] = config('app.image_url') . $extended_img;
                                $data['temp_img_id'] = "none";
                            }
                        }
                    }
                }

                return view('pages.protect_request.scanner.edit_uploaded_image_alt', compact('data'))->render();
            }
        } catch (\Throwable $e) {
            $e->getMessage();
            dd($e->getMessage());
        }
    }

    public function imageMatch($img1, $img2)
    {
        # ... Third party API Python
        $url = "https://imageverify.asign.art/imagecomapareurl";
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => [
                'imageUrl' => $img1,
                'imageUrl1' => $img2,
            ],
            CURLOPT_HTTPHEADER => [
                'Content-Type: multipart/form-data'
            ]
        ]);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            //echo 'cURL error: ' . curl_error($ch);
            curl_close($ch);
            return 0;
        }

        curl_close($ch);
        $result = json_decode($response, true);
        if (isset($result['matchscore'])) {
            if ($result['matchscore'] <= 0) {
                $result['matchscore'] = 0;
            }
            return $result['matchscore'];
        } else {
            //echo 'Error: Invalid response from API';
            return 0;
        }

        return 0;
    }

    public function scannerAppDelete(Request $request)
    {
        try {
            # ... Validations
            $req = $request->only('request_id');
            $rules = [
                'request_id' => 'required',
            ];
            $messages = [];
            $validator = Validator::make($req, $rules, $messages);
            if ($validator->fails()) {
                //$validator->messages();
                dd($validator->messages());
            }

            # ... Get formvalues & process
            $protect_request = ArtworkProtectRequest::where(['id' => $req['request_id']])->first();
            if ($protect_request) {
                if (Storage::disk('s3')->exists($protect_request->object_img_url)) {
                    Storage::disk('s3')->delete($protect_request->object_img_url);
                    $protect_request->object_img_url = "";
                    $protect_request->current_step = "object_match";
                    $protect_request->matching_percentage = 0;
                    $protect_request->save();
                }

                $data = [
                    "object_img" => "",
                    "reference_img" => $protect_request->reference_img_url,
                    "percentage" => $protect_request->matching_percentage
                ];

                return view('pages.protect_request.scanner.forms.object_form', compact('data'))->render();
            }
        } catch (\Throwable $e) {
            //$e->getMessage();
            dd($e->getMessage());
        }
    }

    public function scannerAppMatchingValue(Request $request)
    {
        $input = $request->all();
        $protect_request = ArtworkProtectRequest::where(['id' => $input['request_id']])->first();
        if ($protect_request) {

            $protect_request->matching_percentage = random_int(0, 100);
            $protect_request->save();

            return response()->json([
                "status" => "success",
                "matching_value" => $protect_request->matching_percentage,
            ]);
        }

        return response()->json([
            "status" => "error",
            "matching_value" => 0,
        ]);
    }

    public function scannerAppSave(Request $request)
    {
        try {
            # ... Validations
            $req = $request->only('request_id', 'images', 'type', 'envelope', 'label', 'label_objects', 'is_approved', 'title', 'child_step', 'formtype');
            $rule = [
                'request_id' => 'required',
                'type' => 'required|string|in:inventory_label,auth_label,inventory_label_child,auth_label_child',
                'envelope' => 'required',
                'label' => 'required',
                'is_approved' => 'nullable',
                'images' => 'required|array',
            ];
            $messages = [];
            $validator = Validator::make($req, $rule, $messages);
            if ($validator->fails()) {
                return $this->returnError($validator->messages());
            }

            $validator = $validator->validated();

            # ... Get formvalues & process
            $one_request = ArtworkProtectRequest::findOrFail($req['request_id']);
            if (!$one_request) {
                return $this->returnError("Request not found");
            }

            $images = [];
            $path = UtilsHelper::storagePath();

            foreach ($req['images'] as $image) {
                if ($image) {
                    $image = str_replace($path, '', $image);
                    if (str_contains($image, 'temp/')) {
                        $base_name = pathinfo($image)['basename'];
                        Storage::disk('s3')->move('artworks/temp/' . $base_name, 'artworks/' . $base_name);
                        $image = 'artworks/' . $base_name;
                    }
                    $images[] = $image;
                }
            }

            DB::beginTransaction();
            $change_consumed = true;
            if ($validator['type'] == "inventory_label_child" || $validator['type'] == "auth_label_child") {
                $type = str_replace("_child", "", $validator['type']);

                if ($req['formtype'] != "standalone") {

                    # ... Making child labels array for next / prev pagination
                    $child_step_index = $req['child_step']; // hidden field in form
                    $child_labels = $one_request->child_labels ? $one_request->child_labels : [];
                    $count = count($child_labels);
                    $label_temp = [
                        "index" => $count,
                        "title" => $req['title'],
                        "type" => $validator['type'],
                        "envelope" => $validator['envelope'],
                        "label" => $validator['label'],
                        "images" => $images,
                    ];


                    if ($child_step_index != "on_update") {
                        # ... Update child labels
                        if (isset($child_labels[$child_step_index])) {

                            if ($child_labels[$child_step_index]['label'] != $validator['label']) {
                                $this->checkLabelExist($child_labels[$child_step_index]['label'], $validator['type'], 'old');
                            } else {
                                $change_consumed = false;
                            }

                            $child_labels[$child_step_index] = $label_temp;
                        }

                        if ($count != $child_step_index) {
                            $one_request->child_step = $child_step_index + 1;
                        }
                    } else {
                        # ... Create child labels
                        $child_labels[] = $label_temp;
                        $one_request->child_step = $count + 1;
                    }

                    $one_request->child_labels = $child_labels;
                } else {
                    # ... Inventory / Authenticity labels and its childs
                    $label_value = $one_request->{$type};
                    $label_value["child"][] = [
                        "envelope" => $validator['envelope'],
                        "label" => $validator['label'],
                        "images" => $images
                    ];
                    $one_request->{$type} = $label_value;
                }
            } else {
                # ... inventory_label and auth_label
                $label_value = $one_request->{$validator['type']};
                if (!$label_value) {
                    $label_value = [
                        "envelope" => $validator['envelope'],
                        "label" => $validator['label'],
                        "images" => $images,
                        "child" => []
                    ];
                } else {
                    if ($label_value['label'] != $validator['label']) {
                        $this->checkLabelExist($label_value['label'], $validator['type'], 'old');
                    } else {
                        $change_consumed = false;
                    }
                    $label_value['envelope'] = $validator['envelope'];
                    $label_value['label'] = $validator['label'];
                    $label_value['images'] = $images;
                }
                $one_request->{$validator['type']} = $label_value;

                if ($validator['type'] == "auth_label") {
                    # ... Choose next from next
                    $child_labels = $one_request->child_labels ? count($one_request->child_labels) : 0;
                    if ($child_labels > 0) {
                        $one_request->child_step = $one_request->child_step == null ? 0 : $one_request->child_step + 1;
                    } else {
                        $one_request->child_step = null;
                    }
                }
            }

            if ($change_consumed) {
                if (!$this->checkLabelExist($validator['label'], $validator['type'], 'consumed')) {
                    return $this->returnError("Invalid label");
                }
            }

            if ($validator['type'] == "inventory_label") {
                $one_request->current_step = "auth_label";
            } elseif ($validator['type'] == "auth_label") {
                $one_request->current_step = "inventory_label_child";
            } elseif ($validator['type'] == "inventory_label_child") {
                if ($req['formtype'] != "standalone") {
                    $one_request->current_step = "auth_label_child";
                }
            } elseif ($validator['type'] == "auth_label_child") {
                if ($req['formtype'] != "standalone") {
                    $one_request->current_step = "inventory_label_child";
                }
            }

            if (isset($validator['is_approved']) && $validator['is_approved'] == "true") {
                if ($one_request->status == 'approved') {
                    $result['header'] = $this->headerDataView($one_request);
                } else {
                    $result = $this->approveStatus($one_request);
                }
            } else {
                $result['header'] = $this->headerDataView($one_request);
            }

            if (!$one_request->save()) {
                return $this->returnError("Error while saving data");
            }
            DB::commit();

            $data = [];
            $data['current_step'] = $one_request->current_step;
            $data['labelling'] = $this->processObjectLabelling($one_request);

            $result['object-label'] = view('pages.protect_request.components.object-label', compact('data'))->render();
            $result['scanner'] = $this->showScannerStep($one_request, $req['formtype']);
            $result['status'] = $one_request->status;
            $result['formtype'] = $req['formtype'];
            $result['form_for'] = $one_request->current_step;

            return $result;
        } catch (\Throwable $e) {

            DB::rollBack();
            return $this->returnError($e->getMessage());
        }
    }

    public function envelopeValidator(Request $request)
    {
        $envelope_id = $request->query('envelope_id');
        $valid = url(asset('icons/valid.svg'));
        $in_valid = url(asset('icons/invalid.svg'));

        if ($envelope_id) {
            return response()->json([
                "data" => '<span><img src="' . $valid . '" width="16">Valid</span>',
            ], 200);
        }

        return response()->json([
            "data" => '<span><img src="' . $in_valid . '" width="16">Invalid code</span>',
        ], 200);
    }

    public function labelValidator(Request $request)
    {
        $label_id = $request->query('label_id');
        $current_step = $request->query('current_step');
        $valid = url(asset('icons/valid.svg'));
        $in_valid = url(asset('icons/invalid.svg'));

        try {

            if ($this->checkLabelExist($label_id, $current_step)) {
                return response()->json([
                    "status" => "valid",
                    "data" => '<span><img src="' . $valid . '" width="16">Valid</span>',
                ], 200);
            }

            return response()->json([
                "status" => "",
                "data" => '<span><img src="' . $in_valid . '" width="16">Invalid code</span>',
            ], 200);
        } catch (\Throwable $e) {
            $e->getMessage();
        }
    }

    public function scannerAppVoidSave(Request $request)
    {
        $requestId = $request->input('request_id');
        $current_step = $request->input('current_step');
        $agent = Auth::user();
        $artwork_id = ArtworkProtectRequest::where('id', $requestId)->select('artwork_id')->first();

        $validatedData = $request->validate([
            'envelope_code' => 'required|string',
            'void_remarks' => 'required|string',
            'void_reason_id' => 'required|integer',
            'label_code' => 'required|string',
        ], [
            'required' => 'The :attribute field is required.',
            'string' => 'The :attribute must be a string.',
            'integer' => 'The :attribute must be a integer.',
        ]);
        $validatedData['agent_id'] = $agent->id;
        $validatedData['location_id'] = $agent->branch_office_id;
        $validatedData['request_id'] = $requestId;
        $validatedData['artwork_id'] = $artwork_id['artwork_id'];

        try {
            if ($this->checkLabelExist($validatedData['label_code'], $current_step, 'damaged')) {
                $void = new LabelVoid($validatedData);
                $void->save();
            } else {
                return $this->returnSuccess(['message' => 'Label Already Consumed']);
            }

            return $this->returnSuccess($void, "Label Void created successfully");
        } catch (\Exception $e) {
            return $this->returnError('Failed to create Label Void: ' . $e->getMessage());
        }
    }

    private function checkLabelExist($code, $product, $action = false)
    {
        $user_id = Auth::user()->id;
        if (str_contains($product, 'inventory')) {
            $product_id = UtilsHelper::INVENTORY_PRODUCT_ID;
        } elseif (str_contains($product, 'auth')) {
            $product_id = UtilsHelper::AUTHENTICITY_PRODUCT_ID;
        } else {
            return false;
        }

        $open_status = 'issued';
        if ($action == 'old')
            $open_status = 'consumed';

        $label = DB::table('label_product_details as lpd')
            ->join('labels as l', 'lpd.label_id', '=', 'l.id')
            ->where('l.agent_id', $user_id)
            ->where('lpd.product_id', $product_id)
            ->where('lpd.code', $code)
            ->where('lpd.status', $open_status)
            ->select('lpd.id', 'lpd.status')
            ->first();

        if ($label) {
            if ($action == 'consumed' || $action == 'damaged') {
                LabelProductDetail::where('id', $label->id)->update([
                    'status' => $action,
                    $action => now()
                ]);
            } elseif ($action == 'old') {
                LabelProductDetail::where('id', $label->id)
                    ->where('status', 'consumed')
                    ->update([
                        'consumed' => null,
                        'status' => 'issued'
                    ]);
            }
            return true;
        }
        return false;
    }

    public function removeLabelImage(Request $request)
    {
        $validator = $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:inventory_label,auth_label,inventory_label_child,auth_label_child',
            'image' => 'required|string'
        ]);


        $id = $validator['id'];
        $type = $validator['type'];
        $image = $validator['image'];

        $path = UtilsHelper::storagePath();
        $image = str_replace($path, '', $image);

        try {
            $protect = ArtworkProtectRequest::findOrFail($id);
            if (!$protect) {
                return $this->returnError("Request not found");
            }

            if (Storage::disk('s3')->exists($image)) {
                Storage::disk('s3')->delete($image);
            }

            if (!str_contains($type, '_child')) {
                $label_value = $protect->{$type};
                if ($label_value) {
                    $old_images = $label_value['images'];

                    if (($key = array_search($image, $old_images)) !== false) {
                        unset($old_images[$key]);
                        $old_images = array_values($old_images);
                    }

                    $label_value['images'] = $old_images;
                    $protect->{$type} = $label_value;
                    $protect->save();
                }
            }


            $data = [];
            $data['current_step'] = $protect->current_step;
            $data['labelling'] = $this->processObjectLabelling($protect);

            return [
                'object-label' => view('pages.protect_request.components.object-label', compact('data'))->render(),
                'scanner' => $this->showScannerStep($protect, "")
            ];
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    private function processObjectLabelling($protect)
    {

        $labels = [
            'Inventory' => [],
            'Authenticity' => [],
        ];
        $have_parent = false;
        $have_parent_auth = false;
        $have_parent_inv = false;
        $inventory_text = 'Pending';
        $auth_text = 'Pending';

        if ($protect->status == 'asign-protect' && !$protect->reference_img_url) {
            $verified = $this->asignProtectApproveVerify($protect, $protect->verify_status);
            if ($verified) {
                $inventory_text = 'Click Start Labelling';
                $auth_text = 'Click Start Labelling';
            }
        }

        if ($protect->reference_img_url) {
            $inventory_text = 'In Progress';
            $auth_text = 'In Progress';
        }
        if ($protect->current_step && $protect->current_step != 'preview') {
            $inventory_text = 'In Progress';
            $auth_text = 'In Progress';
        }
        $child_labels = $protect->child_labels;

        $in_child_image = [];
        $au_child_image = [];
        $in_child_label = [];
        $au_child_label = [];

        if ($child_labels) {
            foreach ($child_labels as $lbl) {
                if ($lbl['type'] === "inventory_label_child") {
                    $in_child_label[] = $lbl['label'];
                    $in_child_image = array_merge($in_child_image, $lbl['images']);
                } else if ($lbl['type'] === "auth_label_child") {
                    $au_child_label[] = $lbl['label'];
                    $au_child_image = array_merge($au_child_image, $lbl['images']);
                }
            }
        }

        # ... Inventory Label Images & Labels
        $inventory = $protect->inventory_label;
        if ($inventory) {
            $images = [];
            if ($inventory['images'] && count($inventory['images']) > 0) {
                $images = $inventory['images'];
            }

            foreach ($inventory['child'] as $child) {
                if ($child['images'] && count($child['images']) > 0) {
                    $images = array_merge($images, $child['images']);
                }
            }
            $images = array_merge($images, $in_child_image);
            $in_child_label = array_merge(array_column($inventory['child'], 'label'), $in_child_label);

            $have_parent = true;
            $have_parent_inv = true;
            $labels['Inventory'] = [
                'parent' => $inventory['label'],
                'child' => $in_child_label,
                'images' => $images
            ];
            $inventory_text = '';
        }

        # ... Authenticity Label Images & Labels
        $authenticity = $protect->auth_label;
        if ($authenticity) {
            $images = [];
            if ($authenticity['images'] && count($authenticity['images']) > 0) {
                $images = $authenticity['images'];
            }

            foreach ($authenticity['child'] as $child) {
                if ($child['images'] && count($child['images']) > 0) {
                    $images = array_merge($images, $child['images']);
                }
            }
            $images = array_merge($images, $au_child_image);
            $au_child_label = array_merge(array_column($authenticity['child'], 'label'), $au_child_label);


            $have_parent = true;
            $have_parent_auth = true;
            $labels['Authenticity'] = [
                'parent' => $authenticity['label'],
                'child' => $au_child_label,
                'images' => $images
            ];
            $auth_text = '';
        }

        if ($protect->status == 'approved') {
            $inventory_text = '';
            $auth_text = '';
        }

        $labels['Inventory']['text'] = $inventory_text;
        $labels['Authenticity']['text'] = $auth_text;
        $labels['Inventory']['is_parent'] = $have_parent_inv;
        $labels['Authenticity']['is_parent'] = $have_parent_auth;

        return ['labels' => $labels, 'have_parent' => $have_parent];
    }

    private function approveStatus($protect_request)
    {
        $user_id = auth()->user()->id;
        $id = $protect_request->id;

        $old_status = $protect_request->status;
        $old_status_arr = ArtworkProtectRequest::STATUS[$old_status] ?? null;

        if ($old_status_arr) {
            $next_status = $old_status_arr['next'];
            if ($next_status == '')
                throw new \Exception('Request already ' . $old_status_arr['label']);
            //return $this->returnError('Request already ' . $old_status_arr['label']);

            $column = $old_status_arr['role'] . '_ids';
            if (!in_array($user_id, explode(',', $protect_request->$column)))
                throw new \Exception('You are not authorized to approve this request');
            // return $this->returnError('You are not authorized to approve this request');

            if ($old_status == "asign-protect") {
                $check_status = $protect_request->verify_status;
                $verified = $this->asignProtectApproveVerify($protect_request, $check_status);
                if (!$verified) {
                    throw new \Exception('Please verify all the components before approving the request');
                    //                    return $this->returnError('Please verify all the components before approving the request');
                }
            }

            if ($next_status == "approved")
                $protect_request->approved_at = now();

            $protect_request->status = $next_status;
        }

        $status_timeline = $protect_request->status_timeline;
        if (!isset($status_timeline[$old_status]))
            $status_timeline[$old_status] = [];
        $status_timeline[$old_status] = ['approve' => true, 'date' => now(), 'user_id' => auth()->user()->id];
        $protect_request->status_timeline = $status_timeline;

        $protect_request->save();

        $title = "Approved " . $old_status_arr['label'];
        $activityLog = $this->saveActivityLog($id, $title, null, $old_status_arr['id']);

        return [
            'status' => $protect_request->status,
            'header' => $this->headerDataView($protect_request),
            'activity' => $this->activityDataView($activityLog),
        ];
    }

    public function uploadFiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|max:51200|mimes:jpeg,jpg,png,webp',
        ]);

        if ($validator->fails()) {
            return $this->returnError($validator->errors());
        }

        if (!$request->hasFile('files')) {
            return $this->returnError();
        }

        try {
            $media = [];
            $files = $request->file('files');
            foreach ($files as $file) {
                $name = time() . '$$' . $file->getClientOriginalName();
                $name = str_replace(' ', '_', $name);
                $filePath = 'artworks/temp/' . $name;
                Storage::disk('s3')->put($filePath, file_get_contents($file));
                $tempPath = UtilsHelper::storagePath($filePath);

//                //Temp Image
//                $temp = new TempImage();
//                $temp->request_id = $request->request_id;
//                $temp->current_step = $request->current_step;
//                $temp->img_url = $tempPath;
//                $temp->child_no = 0;
//                $temp->status = 'temp';
//                $temp->save();

                $media[] = [
                    //"id" => $temp->id,
                    "id" => -12,
                    "step" => 'current_step',
                    "image" => $tempPath
                ];
            }
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        return $this->returnSuccess($media, 'Media');
    }
}
