<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WebService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    protected $webService;

    public function __construct(WebService $webService)
    {
        $this->webService = $webService;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'userName' => 'required|string',
            'password' => 'required|string',
        ]);

        $result = $this->webService->login($credentials);

        if ($result['success']) {
            return redirect('/home');
        }

        return back()->withErrors(['userName' => $result['message']])->withInput();
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'userName' => 'required|string|unique:users,userName',
            'number' => 'required|string|unique:users,number',
            'password' => 'required|string|confirmed|min:6',
            'countryCode' => 'nullable|string|max:5',
        ]);

        $result = $this->webService->register($data);

        return redirect('/home');
    }

    public function update(Request $request)
    {
        $user = Auth::user(); // Use Auth user instead of session('user')

        $data = $request->validate([
            'userName' => 'required|string|max:255|unique:users,userName,' . $user->id,
            'countryCode' => 'required|string|max:5',
            'number' => 'required|string|max:15',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle avatar upload if needed
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Storage::delete('public/' . $user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $this->webService->updateUser($user, $data);

        return redirect()->route('web.profile')->with('success', 'Profile updated successfully!');
    }
}