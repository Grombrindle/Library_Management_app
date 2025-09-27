<?php

namespace App\Actions\CourseRequests\Teacher;

use App\Services\CourseRequests\CourseRequestService;
use App\Models\Admin;

class ListOwnRequestsAction
{
    public function __construct(private CourseRequestService $service) {}

    public function execute(Admin $user)
    {
        return $this->service->listTeacherRequests($user);
    }
}
