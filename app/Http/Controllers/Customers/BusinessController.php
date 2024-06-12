<?php

namespace App\Http\Controllers\Customers;

use App\Helpers\UtilsHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Artwork;
use App\Helpers\MasterHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BussinessExport;
use App\Models\CustomerActivityLog;
use App\Models\CustomerBusiness;
use App\Models\ArtworkProtectRequest;
use App\Models\City;
use App\Models\ObjectType;
use App\Models\RepresentationRejectedReason;

class BusinessController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;

    public function index(Request $request)
    {
        $city = Customer::where('account_type', 'business')->select('city')->whereNotNull('city')->groupBy('city')->get();
        return view('pages.customers.business.index', [
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
            $sort = $request->input('sort') ?? 'asc';
            $field_sort = $request->input('field_sort');
            $percentage = $request->input('percentage');
            $query = Customer::where("mobile", '<>', '')->where('account_type', 'business');
            $query->select('id', 'aa_no', 'is_represent_contract', 'full_name', 'display_name', 'email', 'mobile', 'city', 'status', 'account_type', 'is_mobile_verified', 'is_email_verified', 'is_accept_terms', 'aadhaar_no', 'pan_no', 'cin_no', 'gst_no', 'is_pan_verify', 'is_aadhaar_verify', 'profile_completion', UtilsHelper::checkOnline());
            $data = MasterHelper::Search($query, $search_key, $search);
            if (!empty($search) && !empty($search_key)) {
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
            return redirect()->route('business.index');
        }

        $titles = Artwork::select('type_id')->where('created_by', $id)->whereNotNull('type_id')->groupBy('type_id')->get();
        $locations = Artwork::select('location_details')->where('created_by', $id)->whereNotNull('location_details')->groupBy('location_details')->get();
        $objects = ObjectType::where(['status' => 1])->get(['id', 'name']);
        $rejectedReasons = RepresentationRejectedReason::all();
        $data['representation_rejected_reason'] = $rejectedReasons;
        $cities = City::where(['status' => 1, 'is_serviceable' => 1])->get(['id', 'name']);
        $status = ArtworkProtectRequest::STATUS;
        $location_data = [];
        foreach ($locations as $key => $location) {
            $location_data[] = $location->location_details->city;
        }
        $uniqueData = collect($location_data)->unique()->values()->toArray();
        return view('pages.customers.business.view', [
            'data' => $data,
            'titles' => [],
            'locations' => [],
            'objects' => $objects,
            'cities' => $cities,
            'status' => $status,
        ]);
    }

    public function delete($id)
    {
        try {
            $customer = Customer::find($id)->delete();
            return $this->returnSuccess([], "Business deleted successfully");
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
            $query->where('account_type', 'business');
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

            return Excel::download(new BussinessExport($customers), 'customers.xlsx');
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function update(Request $request, $customer_business_id)
    {
        try {
            if ($request->type === 'rejected') {
                CustomerBusiness::where('customer_business_id', $customer_business_id)
                    ->update([
                        'is_verified' => 0,
                        'representation_reject_id' => $request->reject_reason_id,
                        'representation_reject_reason' => $request->rejection_message
                    ]);
                if (!empty($request->rejection_message)) {
                    $message = 'Rejected Representation Reason: ' . $request->reject_reason_text . '-' . $request->rejection_message;
                } else {
                    $message = 'Rejected Representation Reason: ' . $request->reject_reason_text;
                }
            } else if ($request->type === 'accepted') {
                // Handle acceptance scenario
                CustomerBusiness::where('customer_business_id', $customer_business_id)
                    ->update([
                        'is_verified' => 1,
                        'representation_reject_id' => $request->reject_reason_id,
                        'representation_reject_reason' => $request->rejection_message
                    ]);
                $message = 'Accepted Representation';
            } else {
                // Handle acceptance scenario
                CustomerBusiness::where('customer_business_id', $customer_business_id)
                    ->update([
                        'is_verified' => null,
                    ]);
                $message = 'Default Representation';
            }

            $empty_representation = CustomerBusiness::where('customer_id', $request->customer_id)
                ->whereNull('is_verified')->count();


            $customer = Customer::find($request->customer_id);
            $customer->is_represent_contract = $empty_representation ? 0 : 1;
            $customer->status = 'moderation';
            $customer->save();

            $customerStatus = $customer->status; // Assuming 'status' is the attribute name for the customer status


            // update customer activity log
            $log = new CustomerActivityLog();
            $log->customer_id = $request->customer_id;
            $log->tag = 'user';
            $log->tag_id = $request->user_id;
            $log->type = $request->representation_type;
            $log->type_id = $request->representation_id;
            $log->message = $message;
            $log->save();
            $activityLogs = CustomerActivityLog::with('customer', 'user')
                ->where('customer_id', $request->customer_id)
                ->orderByDesc('created_at')
                ->get();
            $customerArtists = CustomerBusiness::with('business')->where('customer_id', $request->customer_id)->orderBy('id', 'desc')->get();


            $header = view('pages.customers.verify-header', [
                'data' => $customer
            ])->render();

            return $this->returnSuccess(['customer' => $customer, 'header' => $header, 'activityLogs' => $activityLogs, 'customerArtists' => $customerArtists, 'customerStatus' => $customerStatus], $message);

        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return $this->returnError($e->getMessage());
        }
    }
}
