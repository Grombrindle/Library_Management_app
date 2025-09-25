<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthService
{
    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    public function clearRememberToken(User $user): void
    {
        $user->remember_token = null;
        $user->save();
    }

    public function logoutSession(Request $request): void
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}


