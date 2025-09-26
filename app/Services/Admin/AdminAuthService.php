<?php
namespace App\Services\Admin;

use Illuminate\Support\Facades\Auth;

class AdminAuthService
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}