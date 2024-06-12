<?php

namespace App\Http\Controllers;


use App\Helpers\UtilsHelper;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{


    public function index(Request $request)
    {   
        $settings = Setting::all();
        // dd($settings);
        return view("pages.setting.index",compact('settings'));
    }


    public function pricingsave(Request $request): JsonResponse
    {  
        // dd($request->slug);
        
        $validator = Validator::make($request->all(), [
            'margin' => 'required|numeric',           
            'markup' => 'required|numeric', 
            'service' => 'required|numeric',
            'shipping' => 'required|numeric',
            'packing' => 'required|numeric', 
        ]);

        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {

            $name = request()->route()->getName();
        
            $margin = $request->input('margin');
            $markup = $request->input('markup');
            $service = $request->input('service');
            $shipping = $request->input('shipping');
            $packing = $request->input('packing');
            
            $data = [
                'margin' => $margin,
                'markup' => $markup,
                'service' => $service,
                'shipping' => $shipping,
                'packing' => $packing,
            ];
            
            $jsonData = json_encode($data);

            if (!empty($request->id) && $request->slug == "pricing_artworks") {
                $setting = Setting::find($request->id);
                if (!$setting) {
                    return response()->json(['success' => false, 'message' => 'Pricing Artwork not found'], 404);
                }
                $setting->value = $jsonData;
                $setting->name = ($name == "pricing.save") ? "Pricing Artworks" : null;
                $setting->slug =  "pricing_artworks" ;
                $setting->save();
                return response()->json(['success' => true, 'data' => $setting, 'message' => 'Pricing Artwork updated successfully']);
            } else {
                $setting = new Setting;
                $setting->value = $jsonData;
                $setting->name = ($name == "pricing.save") ? "Pricing Artworks" : null;
                $setting->slug =  "pricing_artworks" ;
                $setting->save();
                return response()->json(['success' => true, 'data' => $setting, 'message' => 'Pricing Artwork saved successfully']);
            }
        
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function labelsave(Request $request): JsonResponse
    {   
        // dd($request->slug);

        $validator = Validator::make($request->all(), [
            'labelcost' => 'required|numeric',           
            'minlabel' => 'required|numeric', 
        ]);

        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {

            $name = request()->route()->getName();
        
            $label_cost = $request->input('labelcost');
            $min_label = $request->input('minlabel');
            
            $data = [
                'labelcost' => $label_cost,
                'minlabel' => $min_label,
            ];
            
            $jsonData = json_encode($data);

            if (!empty(!empty($request->id) && $request->slug == "label")) {
                $setting = Setting::find($request->id);
                if (!$setting) {
                    return response()->json(['success' => false, 'message' => 'Label Cost not found'], 404);
                }
                $setting->value = $jsonData;
                $setting->name =  "Label";
                $setting->slug =  "label";
                $setting->save();
                return response()->json(['success' => true, 'data' => $setting, 'message' => 'Label Cost updated successfully']);
            } else {
                $setting = new Setting;
                $setting->value = $jsonData;
                $setting->name =  "Label";
                $setting->slug =  "label";
                $setting->save();
                return response()->json(['success' => true, 'data' => $setting, 'message' => 'Label Cost saved successfully']);
            }
        
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function marketsave(Request $request): JsonResponse
    {   
        // dd($request->slug);

        $validator = Validator::make($request->all(), [
            'payment' => 'required|numeric',           
            'expiry' => 'required|numeric', 
            'repayment' => 'required|numeric', 
        ]);

        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {

            $name = request()->route()->getName();
        
            $payment = $request->input('payment');
            $expiry = $request->input('expiry');
            $repayment = $request->input('repayment');
            
            $data = [
                'payment' => $payment,
                'expiry' => $expiry,
                'repayment' => $repayment,
            ];
            
            $jsonData = json_encode($data);

            if (!empty(!empty($request->id) && $request->slug == "market_place")) {
                $setting = Setting::find($request->id);
                if (!$setting) {
                    return response()->json(['success' => false, 'message' => 'Market Place not found'], 404);
                }
                $setting->value = $jsonData;
                $setting->name =  "Market Place";
                $setting->slug =  "market_place";
                $setting->save();
                return response()->json(['success' => true, 'data' => $setting, 'message' => 'Market Place updated successfully']);
            } else {
                $setting = new Setting;
                $setting->value = $jsonData;
                $setting->name =  "Market Place";
                $setting->slug =  "market_place";
                $setting->save();
                return response()->json(['success' => true, 'data' => $setting, 'message' => 'Market Place saved successfully']);
            }
        
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function paymentsave(Request $request): JsonResponse
    {   
        // dd($request->slug);

        $validator = Validator::make($request->all(), [
            'account_name' => 'required',           
            'account_nO' => 'required', 
            'ifsc' => 'required', 
            'iban' => 'required', 
            'swift' => 'required', 
            'branch' => 'required', 
        ]);

        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {

            $name = request()->route()->getName();
        
            $ac_name = $request->input('account_name');
            $ac_no = $request->input('account_nO');
            $ifsc = $request->input('ifsc');
            $iban = $request->input('iban');
            $swift = $request->input('swift');
            $branch = $request->input('branch');
            
            $data = [
                'account_name' => $ac_name,
                'account_nO' => $ac_no,
                'ifsc' => $ifsc,
                'iban' => $iban,
                'swift' => $swift,
                'branch' => $branch,
            ];
            
            $jsonData = json_encode($data);

            if (!empty(!empty($request->id) && $request->slug == "payment_details")) {
                $setting = Setting::find($request->id);
                if (!$setting) {
                    return response()->json(['success' => false, 'message' => 'Payment Details not found'], 404);
                }
                $setting->value = $jsonData;
                $setting->name =  "Payment Details";
                $setting->slug =  "payment_details";
                $setting->save();
                return response()->json(['success' => true, 'data' => $setting, 'message' => 'Payment Details updated successfully']);
            } else {
                $setting = new Setting;
                $setting->value = $jsonData;
                $setting->name =  "Payment Details";
                $setting->slug =  "payment_details";
                $setting->save();
                return response()->json(['success' => true, 'data' => $setting, 'message' => 'Payment Details saved successfully']);
            }
        
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function locationAgent(Request $request)
    {
        try {
            $location_id = $request->route('location_id');
            $agents = User::where('role_id', UtilsHelper::FIELD_AGENT)->where('branch_office_id', $location_id)
                ->orderBy('name', 'asc')
                ->get(['id', 'name']);
            return $this->returnSuccess($agents);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }

    }

}
