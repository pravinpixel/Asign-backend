<?php

namespace App\Http\Controllers\Customers;

use App\Models\ArtworkProtectRequest;
use App\Models\City;
use App\Models\ObjectType;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Artwork;
use App\Helpers\MasterHelper;
use App\Helpers\UtilsHelper;
use App\Exports\CustomersExport;
use App\Models\CustomerActivityLog;
use App\Models\CustomerArtist;
use App\Models\CustomerBusiness;
use App\Models\RepresentationRejectedReason;
use Illuminate\Support\Facades\Log;

class CustomersController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;

    public function index(Request $request)
    {
        $city = Customer::select('city')->whereNotNull('city')->groupBy('city')->get();
        return view('pages.customers.customer.index', [
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
            $type = $request->input('type');
            $login = $request->input('login');
            $city = $request->input('city');
            $status = $request->input('status');
            $field_sort = $request->input('field_sort');
            $percentage = $request->input('percentage');
            $sort = $request->input('sort') ?? 'asc';
            $query = Customer::where("mobile", '<>', '');
            $query->select('id', 'aa_no', 'is_represent_contract', 'full_name', 'display_name', 'email', 'mobile', 'city', 'status', 'account_type', 'register_as', 'company_type', 'is_mobile_verified', 'is_email_verified', 'is_accept_terms', 'aadhaar_no', 'pan_no', 'cin_no', 'gst_no', 'is_pan_verify', 'is_aadhaar_verify', 'profile_completion', UtilsHelper::checkOnline());
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
            if (isset($type) && $type != []) {
                $account_type = explode(',', $type);
                $query->whereIn('account_type', $account_type);
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
                if (sizeof($status_data) > 0) {
                    $query->whereIn('status', $status_data);
                }

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
        if(!$data) {
            return redirect()->route('customer.index');
        }
        $objects = ObjectType::where(['status' => 1])->get(['id', 'name']);
        $rejectedReasons = RepresentationRejectedReason::all();
        $data['representation_rejected_reason'] = $rejectedReasons;
        $cities = City::where(['status' => 1, 'is_serviceable' => 1])->get(['id', 'name']);
        $status = ArtworkProtectRequest::STATUS;
        return view('pages.customers.' . $data->account_type . '.view', [
            'data' => $data,
            'objects' => $objects,
            'cities' => $cities,
            'status' => $status,
        ]);

    }

    public function update(Request $request, $id)
    {
        $cust = Customer::where('id', $id)->first();
        $cust->status = $request->status;
        $cust->save();

        $message = $request->status == 'paused' ? 'Paused the Profile' : 'Verified the Profile';
        // update customer activity log
        $log = new CustomerActivityLog();
        $log->customer_id = $id;
        $log->tag = 'user';
        $log->tag_id = $request->user_id;
        $log->message = $message;
        $log->save();
        // Fetch updated customer data
        $activityLogs = CustomerActivityLog::with('customer', 'user')
            ->where('customer_id', $id)
            ->orderByDesc('created_at')
            ->get();

        $customerArtists = CustomerArtist::with('artist')->where('customer_id', $id)->orderBy('id', 'desc')->get();
        $customerBusinesses = CustomerBusiness::with('business')->where('customer_id', $id)->orderBy('id', 'desc')->get();
        return $this->returnSuccess(['activityLogs' => $activityLogs, 'customerArtists' => $customerArtists, 'customerBusinesses' => $customerBusinesses, 'customerStatus' => $request->status], $message);
    }

    public function statusUpdate(Request $request)
    {

        try {
            $id = $request->route('id');
            $request->validate([
                'status' => 'required|string|in:verified,paused',
            ]);

            $customer = Customer::where('id', $id)->first();
            if (!$customer) {
                return $this->returnError('Customer not found');
            }
            $customer->status = $request->status;
            $customer->save();

            $message = $request->status == 'paused' ? 'Paused the Profile' : 'Verified the Profile';

            $log = new CustomerActivityLog();
            $log->customer_id = $id;
            $log->tag = 'user';
            $log->tag_id = auth()->user()->id;
            $log->message = $message;
            $log->save();

            $activityLogs = CustomerActivityLog::with('customer', 'user')
                ->where('customer_id', $id)
                ->orderByDesc('created_at')
                ->get();

            $result['header'] = view('pages.customers.verify-header', [
                'data' => $customer
            ])->render();
            $result['status'] = $customer->status;
            $result['activityLogs'] = $activityLogs;

            return $this->returnSuccess($result, $message);

        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());

        }
    }


    public function collection(Request $request, $id)
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
            $title = $request->input('type');
            $location = $request->input('location');
            $status = $request->input('status');
            $sort = $request->input('sort') ?? 'desc';
            $query = Artwork::query();
            $query->where('created_by', $id);
            $query->whereJsonContains("menu_details", ['is_show' => true]);
            if (!empty($search) && !empty($search_key)) {
                if ($search_key == 'all') {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orwhere('location_details->city', 'like', '%' . ucfirst($search) . '%')
                        ->orwhere(function ($q) use ($search) {
                            $q->whereHas('type', function ($type_query) use ($search) {
                                $type_query->where('name', 'like', '%' . $search . '%');
                            });
                        });
                } else if ($search_key == 'type') {
                    $query->whereHas('type', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
                } else if ($search_key == 'location') {
                    $query->where('location_details->city', 'like', '%' . ucfirst($search) . '%');
                } else {
                    $query->where($search_key, 'like', '%' . $search . '%');
                }
            }
            if (isset($title) && $title != []) {
                $account_title = explode(',', $title);
                $query->whereIn('type_id', $account_title);
            }
            if (isset($location) && $location != []) {
                $location_data = explode(',', $location);
                $query->whereIn('location_details->city', $location_data);
            }
            if (isset($status) && $status != []) {
                $status_data = explode(',', $status);
                if (count($status_data) == 1) {
                    $status_value = ($status_data[0] == 'verified') ? ['published'] : ['pending', 'draft'];
                    $query->whereIn('status', $status_value);
                }
            }

            $collections = $query->paginate($this->perPage, ['*'], 'page', $this->pageNumber);
            return $this->returnSuccess($collections);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $type = $request->input('type');
            $login = $request->input('login');
            $city = $request->input('city');
            $status = $request->input('status');


            $query = Customer::query();
            $query->select('aa_no', 'display_name', 'account_type', 'city', 'mobile', 'email', 'status');


            if (isset($type) && $type != []) {
                $account_type = explode(',', $type);
                $query->whereIn('account_type', $account_type);
            }


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

            return Excel::download(new CustomersExport($customers), 'customers.xlsx');
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }


    //    protect request

    public function protectRequest(Request $request, $id)
    {

        $search = $request->input('search');
        $search_column = $request->input('search_column');
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $sort = $request->input('sort') ?? 'created_at|desc';

        $city = $request->input('city');
        $object = $request->input('object');
        $request_status = $request->input('status');

        if ($sort)
            $sort = explode('|', $sort);

        $status = ArtworkProtectRequest::STATUS;


        $data = DB::table('artwork_protect_requests as apr')
            ->where('apr.customer_id', $id);

        if ($request_status) {
            if (in_array('review', $request_status))
                $request_status = array_merge($request_status, ['authentication-review', 'inspection-review', 'asign-protect-review']);
            $data->whereIn('apr.status', $request_status);
        }

        $data
            ->join('artworks as a', 'a.id', '=', 'apr.artwork_id')
            ->leftJoin('cities as ct', 'ct.name', '=', 'a.city')
            ->addSelect(DB::raw('IFNULL((SELECT value FROM as_artwork_media WHERE artwork_id = as_apr.artwork_id and tag="featured"), "") as image'))
            ->leftJoin('object_types as o', 'o.id', '=', 'a.type_id');

        if ($city)
            $data->whereIn('a.city', $city);

        if ($object)
            $data->whereIn('o.id', $object);


        $data = $data->select('a.title', 'a.asign_no', 'apr.id', 'apr.request_id','apr.inventory_label','apr.auth_label', 'apr.status', 'apr.status_timeline', 'apr.created_at', 'ct.id as city_id', 'a.city', 'o.name')
            ->addSelect(DB::raw('IFNULL((SELECT value FROM as_artwork_media WHERE artwork_id = as_apr.artwork_id and tag="featured"), "") as image'))
            ->where(function ($query) use ($search, $search_column) {
                if ($search_column == 'title' || !$search_column)
                    $query->orWhere('a.title', 'like', "%$search%");
                if ($search_column == 'asign_no' || !$search_column)
                    $query->orWhere('a.asign_no', 'like', "%$search%");
                if ($search_column == 'status' || !$search_column)
                    $query->orWhere('apr.status', 'like', "%$search%");
            })
            ->orderBy($sort[0], $sort[1])
            ->paginate($per_page, ['*'], 'page', $page);


        $total = $data->total();

        return [
            'table' => view('pages.customers.artwork.protect-table', compact('data', 'status'))->render(),
            'pagination' => view('components.pagination', compact('data'))->render(),
            'total' => $total,
        ];
    }


    public function myStudio(Request $request)
    {
        $search = $request->input('search');
        $search_column = $request->input('search_column');
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 10);
        $sort = $request->input('sort') ?? 'created_at|desc';
        $customer_id = $request->route('id');

        $type = $request->input('type');

        if ($sort)
            $sort = explode('|', $sort);

        $data = DB::table('artworks as a')
            ->where('a.created_by', $customer_id)
            ->whereJsonContains("menu_details", ['is_show' => true])
            ->leftJoin('object_types as o', 'o.id', '=', 'a.type_id')
            ->leftJoin('artwork_protect_requests as apr', 'apr.artwork_id', '=', 'a.id');

        if ($type)
            $data->whereIn('a.type_id', $type);

        $data = $data
            ->select('a.created_at as aging', 'o.name as object_type', 'a.title', 'a.asign_no', 'a.id', 'a.created_at', 'apr.status', 'apr.customer_id')
            ->addSelect(DB::raw('IFNULL((SELECT value FROM as_artwork_media WHERE artwork_id = as_a.id and tag="featured"), "") as image'))
            ->addSelect(DB::raw('IFNULL((SELECT count(id) FROM as_customer_wishlists WHERE artwork_id = as_a.id), 0) as likes'))
            ->addSelect(DB::raw('IFNULL((SELECT count(id) FROM as_customer_artwork_views WHERE artwork_id = as_a.id), 0) as views'))
            ->where(function ($query) use ($search, $search_column) {
                if ($search_column == 'title' || !$search_column)
                    $query->orWhere('a.title', 'like', "%$search%");
                if ($search_column == 'asign_no' || !$search_column)
                    $query->orWhere('a.asign_no', 'like', "%$search%");
                if ($search_column == 'object_type' || !$search_column)
                    $query->orWhere('o.name', 'like', "%$search%");

            })
            ->orderBy($sort[0], $sort[1])
            ->paginate($per_page, ['*'], 'page', $page);
        $total = $data->total();

        return [
            'table' => view('pages.customers.artwork.studio-table', compact('data'))->render(),
            'pagination' => view('pages.customers.artwork.studio-pagination', compact('data'))->render(),
            'total' => $total,
        ];

    }
}
