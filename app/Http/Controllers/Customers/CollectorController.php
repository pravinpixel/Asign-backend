<?php

namespace App\Http\Controllers\Customers;

use App\Helpers\UtilsHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Artwork;
use App\Helpers\MasterHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CollectorExport;
use App\Models\ArtworkProtectRequest;
use App\Models\City;
use App\Models\ObjectType;

class CollectorController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;
    public function index(Request $request)
    {
        $city = Customer::where('account_type', 'collector')->select('city')->whereNotNull('city')->groupBy('city')->get();
        return view('pages.customers.collector.index', [
            'cities' => $city
        ]);
    }
    public function get(Request $request)
    {
        if ($request->has('page')) {
            $this->pageNumber = $request->get('page');
        }
        if ($request->has('per_page')) {
            $this->perPage = $request->get('per_page');
        }
        try {
            $search = $request->input('search');
            $search_key = $request->input('search_key');
            $login = $request->input('login');
            $city = $request->input('city');
            $status = $request->input('status');
            $field_sort = $request->input('field_sort');
            $percentage = $request->input('percentage');
            $sort = $request->input('sort') ?? 'asc';
            $query = Customer::where("mobile", '<>', '')->where('account_type', 'collector');
            $query->select('id', 'aa_no', 'is_represent_contract', 'full_name', 'display_name', 'email', 'mobile', 'city', 'status', 'account_type', 'register_as', 'company_type', 'is_mobile_verified', 'is_email_verified', 'is_accept_terms', 'aadhaar_no', 'pan_no', 'cin_no', 'gst_no', 'is_pan_verify', 'is_aadhaar_verify', 'profile_completion', UtilsHelper::checkOnline());
            $data = MasterHelper::Search($query, $search_key, $search);
            if (!empty($search)  && !empty($search_key)) {
                if ($search_key == 'all') {
                    $query->where('aa_no', 'like', '%' . $search . '%')
                        ->orwhere('full_name', 'like', '%' . $search . '%')
                        ->orwhere('city', 'like', '%' . $search . '%')
                        ->orwhereIn('id', $data);
                } else if ($search_key == 'email' || $search_key == 'mobile') {
                    $query->whereIn('id', $data);
                } else {
                    $query->where($search_key, 'like', '%' . $search . '%');
                }
            }
            if (isset($city) && $city != []) {
                $city_data = explode(',', $city);
                $query->whereIn('city', $city_data);
            }
            if (isset($login) && $login != []) {
                $login_data = explode(',', $login);
                if (count($login_data) == 1)
                    $query->having('is_online', $login_data[0]);
            }
            if (isset($status) && $status != []) {
                $status_data = explode(',', $status);
                $query->whereIn('status', $status_data);
                // if (count($status_data) == 1) {
                //     $status_value = MasterHelper::StatusSearch($query, $status_data);
                //     $query->whereIn('id', $status_value);
                // }
            }
            if ($sort == 'recent' && $field_sort == []) {
                $query->orderBy('created_at', 'desc');
            } elseif ($field_sort == []) {
                $query->orderByRaw("CAST(SUBSTRING_INDEX(aa_no, 'AA', -1) AS UNSIGNED) {$sort}");
            }
            if (isset($field_sort) && $field_sort != []) {
                $fieled = explode(',', $field_sort);
                if ($fieled[0] == 'status') {
                    $sort_data = MasterHelper::StatusSort($query, $fieled[1]);
                    $query->orderByRaw("field(id," . implode(',', $sort_data) . ")");
                } else {
                    $query->orderBy($fieled[0], $fieled[1]);
                }
            }
            if (isset($percentage) && $percentage != []) {
                $percentage_data = explode(',', $percentage);
                $percentage_data_alt = implode('-', $percentage_data);
                $percentage_data_final = explode('-', $percentage_data_alt);
                $min = min($percentage_data_final);
                $max = max($percentage_data_final);

                $query->whereBetween('profile_completion', [$min, $max]);
            }
            $customers = $query->paginate($this->perPage, ['*'], 'page', $this->pageNumber);
            return $this->returnSuccess($customers);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }
    public function view(Request $request, $id)
    {
        $data = Customer::find($id);
        if(!$data){
            return redirect()->route('collector.index');
        }

        $titles = Artwork::select('type_id')->where('created_by', $id)->whereNotNull('type_id')->groupBy('type_id')->get();
        $locations = Artwork::select('location_details')->where('created_by', $id)->whereNotNull('location_details')->groupBy('location_details')->get();
        $objects=ObjectType::where(['status' => 1])->get(['id', 'name']);
        $cities = City::where(['status' => 1, 'is_serviceable' => 1])->get(['id', 'name']);
        $status = ArtworkProtectRequest::STATUS;
        $location_data = [];
        foreach ($locations as $key => $location) {
            $location_data[] = $location->location_details->city;
        }
        $uniqueData = collect($location_data)->unique()->values()->toArray();

        return view('pages.customers.collector.view', [
            'data' => $data,
             'titles' => [],
             'locations' => [] ,
            'objects' => $objects,
            'cities' => $cities,
            'status' => $status,
        ]);
    }
    public function delete($id)
    {
        try {
            $customer = Customer::find($id)->delete();
            return $this->returnSuccess([], "collector deleted successfully");
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }
    public function export(Request $request)
    {
        try {
            $login = $request->input('login');
            $city = $request->input('city');
            $status = $request->input('status');


            $query = Customer::query();
            $query->where('account_type', 'collector');
            $query->select('aa_no', 'display_name', 'account_type', 'city', 'mobile', 'email', 'status');


            if (isset($city) && $city != []) {
                $city_data = explode(',', $city);
                $query->whereIn('city', $city_data);
            }


            if (isset($login) && $login != []) {
                $login_data = explode(',', $login);
                if (count($login_data) == 1) {
                    $query->having('is_online', $login_data[0]);
                }
            }


            if (isset($status) && $status != []) {
                $status_data = explode(',', $status);
                if (count($status_data) == 1) {
                    $status_value = MasterHelper::StatusSearch($query, $status_data);
                    $query->whereIn('id', $status_value);
                }
            }

            $customers = $query->get();

            return Excel::download(new CollectorExport($customers), 'customers.xlsx');
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }
}
