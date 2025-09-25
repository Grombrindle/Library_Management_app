<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Users\UserAuthService;

class LogoutUserAction
{
    public function __construct(private UserAuthService $authService) {}

    public function __invoke(User $user, Request $request): void
    {
        $this->authService->clearRememberToken($user);
        $this->authService->revokeAllTokens($user);
        $this->authService->logoutSession($request);
    }
}


