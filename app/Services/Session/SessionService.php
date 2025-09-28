<?php

namespace App\Services\Session;

use App\Models\User;
use App\Models\LectureRating;
use App\Models\CourseRating;
use App\Models\TeacherRating;
use App\Models\ResourceRating;
use App\Models\Report;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SessionService
{
    public function createUser($request)
    {
        $validator = \Validator::make($request->all(), [
            'userName' => 'required|string|unique:users,userName',
            'number' => 'required|string|unique:users,number',
        ], [
            'userName.unique' => 'Already Used',
            'number.unique' => 'Already Used',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'reason' => $validator->errors(), 'code' => 422];
        }

        $userName = $request->input('userName');
        $number = $request->input('number');
        $password = Hash::make($request->input('password'));
        $countryCode = "+963";

        $user = User::create([
            'userName' => $userName,
            'number' => $number,
            'password' => $password,
            'countryCode' => $countryCode,
            'isBanned' => 0,
            'counter' => 0,
        ]);

        $token = $user->createToken('API Token Of ' . ($user->name ?? $user->userName ?? 'User'))->plainTextToken;
        $user->remember_token = $token;
        $user->save();
        Auth::login($user);

        return ['success' => true, 'token' => $token, 'user' => $user];
    }

    public function loginUser($request)
    {
        $credentials = $request->validate([
            'userName' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('userName', $credentials['userName'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            $token = $user->createToken('API Token')->plainTextToken;
            $user->remember_token = $token;
            $user->save();
            return ['success' => true, 'token' => $token, 'user' => $user];
        }

        return ['success' => false, 'reason' => 'Invalid Credentials', 'code' => 401];
    }

    public function banCurrentUser()
    {
        $user = Auth::user();

        if ($user->isBanned) {
            return ['success' => false, 'reason' => 'Already banned', 'code' => 400];
        }

        $user->isBanned = true;
        $user->remember_token = null;
        $user->save();
        $user->tokens()->delete();

        return ['success' => true];
    }

    public function banUser($id = null, $type = null, $ratingId = null)
    {
        $user = $id ? User::find($id) : Auth::user();

        if ($type && $ratingId) {
            $rating = match ($type) {
                'lecture' => LectureRating::find($ratingId),
                'course' => CourseRating::find($ratingId),
                'teacher' => TeacherRating::find($ratingId),
                'resource' => ResourceRating::find($ratingId),
                default => null,
            };
            if ($rating) {
                $rating->isHidden = true;
                $rating->save();
            }
        }

        if ($user->isBanned) {
            return ['success' => false, 'reason' => 'Already banned', 'code' => 400];
        }

        $user->isBanned = true;
        $user->remember_token = null;
        $user->save();
        $user->tokens()->delete();

        $report = Report::find(session('report'));
        if ($report) {
            $report->status = "BANNED";
            $report->handled_by_id = Auth::id();
            $report->save();
        }

        $data = ['id' => $id, 'name' => $user->userName, 'message' => 'banned'];
        session(['user_info' => $data]);
        session(['link' => '/reports']);

        return ['success' => true];
    }
    public function logoutUser()
    {
        $user = Auth::user();
        if ($user) {
            $user->remember_token = null;
            $user->save();
            $user->tokens()->delete();
        }
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return ['success' => true];
    }
}