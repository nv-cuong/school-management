<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        if(!empty(Auth::check()))
        {
            if (Auth::user()->user_type == 1)
            {
                return redirect('admin/dashboard');
            } 
            else if (Auth::user()->user_type == 2)
            {
                return redirect('teacher/dashboard');
            } 
            else if (Auth::user()->user_type == 3)
            {
                return redirect('student/dashboard');
            } 
            else if (Auth::user()->user_type == 4)
            {
                return redirect('parent/dashboard');
            }
        }
        return view('auth.login');
    }

    public function authLogin(Request $request)
    {
        $remember = !empty($request->remember) ? true : false;
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], true))
        {
            if (Auth::user()->user_type == 1)
            {
                return redirect('admin/dashboard');
            } 
            else if (Auth::user()->user_type == 2)
            {
                return redirect('teacher/dashboard');
            } 
            else if (Auth::user()->user_type == 3)
            {
                return redirect('student/dashboard');
            } 
            else if (Auth::user()->user_type == 4)
            {
                return redirect('parent/dashboard');
            }
        } 
        else 
        {
            return redirect()->back()->with('error', 'Please enter currect email and password');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect(url(''));
    }

    public function forgotPassword()
    {
        return view('auth.forgot');
    }

    public function changePassword(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();

        if(!empty($user))
        {
            $user->remember_token = Str::random(30);
            $user->save();

            Mail::to($user->email)->send(new ForgotPasswordMail($user));

            return redirect()->back()->with('success', 'Please check your email and reset your password');
        }
        else
        {
            return redirect()->back()->with('error', 'Email not found in the system');
        }
    }

    public function reset(Request $request, $token)
    {
        $user = User::where('remember_token', '=', $request->remember_token)->first();

        if(!empty($user))
        {
            $data['user'] = $user;
            return view('auth.reset', $data);
        }
        else
        {
            abort(404);
        }
    }

    public function resetPassword(Request $request, $token)
    {
        if($request->password == $request->confrim_password)
        {
            $user = User::where('remember_token', '=', $token)->first();
            $user->password = Hash::make($request->password);
            $user->remember_token = Str::random(30);
            $user->save();

            return redirect(url(''))->with('success', 'Password successfully reset');
        }
        else
        {
            return redirect()->back()->with('error', 'Password and confrim password does not match');
        }
    }
}
