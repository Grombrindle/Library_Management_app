<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WebController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'userName' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('userName', $credentials['userName'])->first();

        // Primitive check for username and password
        if ($user && Hash::check($credentials['password'], $user->password)) {

            session(['user' => $user->id]);

            // Simulate login success (set session or redirect as needed)
            return redirect('/home');
        }

        return back()->withErrors([
            'userName' => 'Invalid username or password.',
        ])->withInput();
    }
    public function register(Request $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'userName' => 'required|string|unique:users,userName',
        //     'number' => 'required|string|unique:users,number',

        //     // 'deviceId' => 'required|string|unique:users,deviceId',

        // ], [
        //     'userName.unique' => 'Already Used',
        //     'number.unique' => 'Already Used',

        //     // 'deviceId.unique' => 'Already Used',

        // ]);//this will check if these are unique or already in use by other users
        // //we return each one that wasn't unique so the frontend can highlight all the fields that are already in use

        // if ($validator->fails()) {
        //     // Return all validation errors
        //     return response()->json([
        //         'success' => false,
        //         'reason' => $validator->errors(),
        //     ], 422);
        // }
        // $userAttributes = $request->validate([
        //     $userName = $request->input('userName'),
        //     $number = $request->input('number'),
        //     $password = Hash::make($request->input('password')),


        //     // $deviceId = $request->input('devceId'),

        //     $countryCode = "+963",
        // ]);

        // $user = User::create([
        //     'userName' => $userName,
        //     'number' => $number,
        //     'password' => $password,
        //     'countryCode' => $countryCode,
        //     'isBanned' => 0,
        //     'counter' => 0,
        //     // 'deviceId' => $deviceId,

        // ]);
        // $token = $user->createToken('API Token Of' . $user->name)->plainTextToken;
        // $user->remember_token = $token;
        // $user->save();
        // Auth::login($user);
        // return response()->json(['success' => 'true', 'token' => $token, 'user' => $user]);//we return a "success" field so the frontend can see if the sign up process failed or not

    }

    public function update(Request $request)
    {
        $user = User::findOrFail(session('user'));

        $request->validate([
            'userName' => 'required|string|max:255|unique:users,userName,' . $user->id,
            'countryCode' => 'required|string|max:5',
            'number' => 'required|string|max:15',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only('userName', 'countryCode', 'number');

        // if ($request->hasFile('avatar')) {
        //     // Delete old avatar if exists
        //     if ($user->avatar) {
        //         Storage::delete('public/' . $user->avatar);
        //     }

        //     $path = $request->file('avatar')->store('avatars', 'public');
        //     $data['avatar'] = $path;
        // }

        $user->update($data);

        return redirect()->route('web.profile')->with('success', 'Profile updated successfully!');
    }
}
