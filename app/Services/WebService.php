<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class WebService
{
    /**
     * Handle web login
     */
    public function login(array $credentials)
    {
        $user = User::where('userName', $credentials['userName'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Log in using Laravel session
            Auth::login($user);

            return [
                'success' => true,
                'user' => $user
            ];
        }

        return [
            'success' => false,
            'message' => 'Invalid username or password.'
        ];
    }

    /**
     * Handle web registration
     */
    public function register(array $data)
    {
        $user = User::create([
            'userName' => $data['userName'],
            'number' => $data['number'],
            'password' => Hash::make($data['password']),
            'countryCode' => $data['countryCode'] ?? '+963',
            'isBanned' => 0,
            'counter' => 0,
        ]);

        Auth::login($user);

        return [
            'success' => true,
            'user' => $user
        ];
    }

    /**
     * Update user profile
     */
    public function updateUser(User $user, array $data)
    {
        $user->update($data);

        return $user;
    }
}