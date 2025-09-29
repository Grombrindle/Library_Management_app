<?php

namespace App\Actions\CourseRequests\Teacher;

use App\Services\CourseRequestService;
use App\Models\Admin;

class CreateRequestFormAction
{
    public function __construct(private CourseRequestService $service) {}

    public function execute(Admin $user)
    {
        return $this->service->showCreateForm($user);
    }
}
