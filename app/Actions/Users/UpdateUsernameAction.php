<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Services\Users\UserProfileService;

class UpdateUsernameAction
{
    public function __construct(private UserProfileService $profileService) {}

    public function __invoke(User $user, string $newUserName): void
    {
        $this->profileService->updateUsername($user, $newUserName);
    }
}


