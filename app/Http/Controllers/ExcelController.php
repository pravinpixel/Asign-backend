<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerKycStatus;
use App\Models\Customer;
use App\Exports\CustomerKycStatusExport;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ExcelController extends Controller
{
    public function index(Request $request)
    {
        $customer=Customer::all();
        return view('pages.excel.index',compact('customer'));
    }
    public function downloadExcel(Request $request)
    {
        $fromDate = $request->input('from');
        $toDate = $request->input('to');
        $customer_id = $request->input('customer');
    
        if ($fromDate && $toDate) {
            $fromDate = Carbon::parse($fromDate);
            $toDate = Carbon::parse($toDate);
    
            $data = CustomerKycStatus::whereDate('created_at', '>=', $fromDate)
                                      ->whereDate('created_at', '<=', $toDate)
                                      ->with('customer:id,email,mobile,display_name')
                                      ->get();
                                      
        } elseif($customer_id) {
            $data = CustomerKycStatus::where('customer_id', '=', $customer_id)
                                      ->with('customer:id,email,mobile,display_name')->get();
        }else{
            $data = CustomerKycStatus::with('customer:id,email,mobile,display_name')->get();
        }
    
        $result = [];
        foreach($data as $d) {
            $temp = [
                'id' => $d->id,
                'customer_id' => $d->customer_id,
                'display_name' => $d->customer->display_name ?? null,
                'email' => $d->customer->email ?? null,
                'mobile' => $d->customer->mobile ?? null,
                'type' => $d->type,
                'request' => $d->request,
                'response' => $d->response,
                'status' => $d->status,
                'created_at' => $d->created_at,
            ];
            $result[] = $temp;
        }
    
       
        $collection = new Collection($result);
    
        return \Excel::download(new CustomerKycStatusExport($collection), 'customer_kyc_status.xlsx');
    }
}
