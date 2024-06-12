<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {

      
        return view('auth.forgot');
    }

    public function submitForgotPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            
        ]);

        DB::table('password_reset_tokens')
        ->where('email',$request->input('email'))
        ->delete();

      
        $token = Str::random(64);
       

        DB::table('password_reset_tokens')->insert([
            'email'=>$request->input('email'),
            'token'=>$token,
            'created_at'=>Carbon::now()
        ]);

        
        

        // Mail::send('email.forgotpassword',['token'=>$token],function($message) use($request){
        //     $message->to($request->input('email'));
        //     $message->subject('Reset Password');
        // });
        // $url = 'http://127.0.0.1:8000/forgot-password?email=' . $request->input('email') . '&token=' . $token;

        
        $url = route('reset.password.get', ['token' => $token]);
        $url .= '?email=' . urlencode($request->input('email'));

        return back()->with('message', 'we have emailed you a reset password link')
                    ->with('resetLink', $url);

    }

    public function showResetPasswordForm(Request $request,$token)
    {
        $email =$request->input('email');
      
        return view('auth.forgotPasswordLink',['token'=>$token, 'email' => $email]);
    }

    public function submitResetPasswordForm(Request $request)
    {  
        $request->validate([
          'email' => 'required|email|exists:users',
          'password' =>'required|min:6|confirmed',
          'password_confirmation' => 'required'
       ]);

       $password_reset_request = DB::table('password_reset_tokens')
       ->where('email',$request->input('email'))
       ->where('token',$request->token)
       ->first();

       if(!$password_reset_request){
        return back()->with('error','Invalid Token!');
       }

       User::where('email',$request->input('email'))
       ->update(['password'=>Hash::make($request->input('password'))]);
    
       DB::table('password_reset_tokens')
       ->where('email',$request->input('email'))
       ->delete();

       return redirect('/login')->with('message','Your password has been changed!');
    }
}
