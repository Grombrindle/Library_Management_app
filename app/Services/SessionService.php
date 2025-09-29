<?php

namespace App\Services;

use App\Models\User;
use App\Models\Admin;
use App\Models\Report;
use App\Models\LectureRating;
use App\Models\TeacherRating;
use App\Models\CourseRating;
use App\Models\ResourceRating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SessionService
{
    /**
     * Register a new user
     */
    public function createUser(array $data)
    {
        $user = User::create([
            'userName' => $data['userName'],
            'number' => $data['number'],
            'password' => Hash::make($data['password']),
            'countryCode' => $data['countryCode'] ?? '+963',
            'isBanned' => 0,
            'counter' => 0,
        ]);

        $token = $user->createToken('API Token Of ' . ($user->name ?? $user->userName ?? 'User'))->plainTextToken;
        $user->remember_token = $token;
        $user->save();

        Auth::login($user);

        return ['user' => $user, 'token' => $token];
    }

    /**
     * Login a user
     */
    public function loginUser(array $credentials)
    {
        $user = User::where('userName', $credentials['userName'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            $token = $user->createToken('API Token')->plainTextToken;
            $user->remember_token = $token;
            $user->save();

            Auth::login($user);

            return ['success' => true, 'user' => $user, 'token' => $token];
        }

        return ['success' => false, 'message' => 'Invalid Credentials'];
    }

    /**
     * Web login with Admin check
     */
    public function loginWeb(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $admin = Admin::where('userName', $credentials['userName'])->first();
            if ($admin && Hash::check($credentials['password'], $admin->password)) {
                Auth::login($admin);
            }
            return true;
        }

        return false;
    }

    /**
     * Ban a user and optionally hide ratings
     */
    public function banUser(User $user, $reportId = null, $type = null, $ratingId = null)
    {
        // Hide rating if specified
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
            return ['success' => false, 'message' => 'Already banned'];
        }

        $user->isBanned = true;
        $user->remember_token = null;
        $user->save();
        $user->tokens()->delete();

        if ($reportId) {
            $report = Report::find($reportId);
            if ($report) {
                $report->status = "BANNED";
                $report->handled_by_id = Auth::id();
                $report->save();
            }
        }

        return ['success' => true, 'user' => $user];
    }

    /**
     * Logout the current user
     */
    public function logoutUser()
    {
        $user = Auth::user();
        if ($user) {
            $user->remember_token = null;
            $user->save();
            $user->tokens()->delete();
        }

        Auth::guard('web')->logout();
    }

    /**
     * Return the currently authenticated user
     */
    public function currentUser()
    {
        return Auth::user();
    }
}