<?php

namespace App\Actions\Admin;

use Illuminate\Support\Facades\Auth;

class AdminLogoutAction
{
    public function __construct()
    {
    }

    public function execute()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}