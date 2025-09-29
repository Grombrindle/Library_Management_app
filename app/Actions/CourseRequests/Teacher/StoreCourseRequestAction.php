<?php

namespace App\Actions\CourseRequests\Teacher;

use App\Services\CourseRequestService;
use App\Models\Admin;

class StoreCourseRequestAction
{
    public function __construct(private CourseRequestService $service) {}

    public function execute(Admin $user, array $data)
    {
        return $this->service->storeRequest($user, $data);
    }
}
