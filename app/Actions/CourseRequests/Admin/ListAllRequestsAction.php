<?php

namespace App\Actions\CourseRequests\Admin;

use App\Services\CourseRequestService;
use App\Models\Admin;

class ListAllRequestsAction
{
    public function __construct(private CourseRequestService $service) {}

    public function execute(Admin $user)
    {
        return $this->service->listAdminRequests($user);
    }
}
