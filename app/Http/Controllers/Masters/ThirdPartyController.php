<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThirdParty;
use Illuminate\Support\Facades\Validator;

class ThirdPartyController extends Controller
{
    public function index(Request $request)
    {

        $third_party = ThirdParty::all()->toArray();

        $verif5digital = $third_party[array_search('veri5digital', array_column($third_party, 'name'))];
        $sms = $third_party[array_search('sms', array_column($third_party, 'name'))];


        return view('pages.masters.ThirdParty.index', [
            'verif5digital' => $verif5digital['variables'],
            'sms' => $sms['variables'],
        ]);

    }


    public function save(Request $request)
    {

        $name = $request->input('name');

        $valid_array = [];

        if ($name == 'veri5digital') {
            $valid_array = [
                'name' => 'required',
                'api_url' => 'required',
                'client_code' => 'required',
                'api_key' => 'required',
                'salt' => 'required',
            ];
        } else if ($name = 'sms') {
            $valid_array = [
                'name' => 'required',
                'key' => 'required',
                'secret' => 'required',
                'sender_id' => 'required',
                'region' => 'required',
                'entity_id' => 'required',
            ];
        }


        $validator = Validator::make($request->all(), $valid_array);

        if ($validator->fails())
            return $this->returnError($validator->errors());


        try {

            $third_party = ThirdParty::where('name', $name)->first();
            if (!$third_party) {
                throw new \Exception('Data Not found');
            }

            $third_party->name = $request->input('name');

            $variables = [];
            foreach ($valid_array as $key => $value) {
                $variables[$key] = $request->input($key);
            }

            $third_party->variables = $variables;
            $third_party->updated_by = auth()->user()->id;

            $third_party->save();

            return $this->returnSuccess([
                'id' => $third_party->id
            ], 'Third party updated successfully');

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
        }

    }


}
