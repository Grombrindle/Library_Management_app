<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserProfileService
{
    public function updateUsername(User $user, string $newUserName): void
    {
        if ($newUserName === 'admin') {
            throw ValidationException::withMessages(['userName' => 'Reserved name']);
        }
        $user->userName = $newUserName;
        $user->save();
    }

    public function ban(User $user): void
    {
        if ($user->isBanned) {
            return;
        }
        $user->isBanned = true;
        $user->save();
    }
}


