<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Contrloer class for authenticating users
class AuthController extends Controller
{

    public function login()
    {
        $user = session('username');
        if (isset($user)) {
            return redirect('/');
        }
        return view('login');
    }

    // validate login
    public function auth(Request $request)
    {
        if ($request->isMethod('POST')) {
            $userName = $request->input('username');
            $password = $request->input('password');
            $user = \DB::table('user')->where('username', $userName)->first();
            $is_success = false;
            if (isset($user) && \Hash::check($password, $user->password)) {
                session(['username' => $user->username]);
                session(['full_name' => $user->full_name]);
                session(['type' => $user->role]);
                session(['id' => $user->id]);
                $is_success = true;
            }
            if ($is_success == true) {
                return redirect('/');
            } else {
                $error = true;
                return view('login', compact('error'));
            }
        }
        return "";
    }

    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
}