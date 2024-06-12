<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class ChangePasswordController extends Controller
{
    public function index()
    {  
        return view('auth.changepassword');
    }

    public function updatepassword(Request $request)

    {$id = Auth::id();
    $request->validate([
        'currentpassword' => 'required|min:6',
        'newpassword' => 'required|min:6',
        'newpassword_confirmation' => 'required_with:password|same:newpassword|min:6'
    ]);

    try {
        $user = User::find($id);

        
        if (Hash::check($request->input('currentpassword'), $user->password)) {
            
            $user->password = Hash::make($request->input('newpassword'));
            $user->save();

            return back()->withSuccess('Password updated successfully');
        } else {
            return back()->withErrors(['currentpassword' => 'Current password is incorrect']);
        }
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
    }

}
