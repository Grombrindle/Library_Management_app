<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Services\Users\UserAuthService;
use App\Services\Users\UserProfileService;

class BanUserAction
{
    public function __construct(
        private UserProfileService $profileService,
        private UserAuthService $authService
    ) {}

    public function __invoke(User $user): void
    {
        $this->profileService->ban($user);
        $this->authService->clearRememberToken($user);
        $this->authService->revokeAllTokens($user);
    }
}


