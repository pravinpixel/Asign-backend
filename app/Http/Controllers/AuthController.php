<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{

    public function index()
    {
        return view('auth.login');
    }
    public function emailcheck(Request $request)
    {
        $email = $request->input('email');
        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                return response()->json(['exists' => true,'user'=>$user]);
            } else {
                return response()->json(['exists' => false]);
            }
        }
        return response()->json(['exists' => 'not']);
    }
    public function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email',
            'password' => 'required',

        ]);
         $credentials = ['email' => request('email'), 'password' => request('password'),'status'=>1];
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if(access()->hasAccess('customer.view')){
               return redirect()->route('customer.index');
           }else{
            return redirect()->route('dashboard.view');
           }

        }
        return back()->withErrors([
            'email' => 'Incorrect Email or Password. Try again!',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index.login')->withSuccess('You have logged out successfully!');
    }

    public function get(Request $request)
    {    $user=Auth::user();
         return view('auth.view',['user'=>$user]);
    }
    public function edit(Request $request)
    {    $user=Auth::user();
         return view('auth.edit',['user'=>$user]);
    }

    public function update(Request $request)
    {
        $id = Auth::id();
        $form_data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id . ',id',
            'mobile_number' => 'required|numeric|digits:10',


        ]);

        try {
            $user = User::find($id);

            if (!$user) {
                return back()->withErrors(['error' => 'User not found']);
            }


            $user->name = $request->input('name');
            $user->mobile_number = $request->input('mobile_number');
            $user->email = $request->input('email');

            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $exten = $file->getClientOriginalExtension();
                $fileName = time() . "." . $exten; // Define $fileName here
                Storage::disk('s3')->put($fileName, file_get_contents($file));
                $user->profile_image = $fileName; // Set the profile_image attribute
            } elseif ($request->input('avatar_remove')) {
                // Remove the profile image
                $user->profile_image = null;
            }


            $user->update();

            return redirect('auth')->withSuccess('Profile updated successfully');
        } catch (\Exception $e) {


            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

}
