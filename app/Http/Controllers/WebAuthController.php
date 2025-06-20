<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class WebAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.web-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'userName' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('web.profile');
        }

        return back()->withErrors([
            'userName' => 'Invalid username or password',
        ])->withInput();
    }

    public function showRegisterForm()
    {
        return view('auth.web-register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userName' => 'required|string|unique:users,userName',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'userName' => $request->userName,
            'password' => Hash::make($request->password),
            'isBanned' => 0,
            'counter' => 0,
            'number' => '',
            'countryCode' => '',
        ]);
        Auth::login($user);
        return redirect()->route('web.profile');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
} 