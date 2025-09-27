<?php

namespace App\Actions\CourseRequests\Admin;

use App\Services\CourseRequests\CourseRequestService;
use App\Models\Admin;

class ApproveRequestAction
{
    public function __construct(private CourseRequestService $service) {}

    public function execute(Admin $user, int $id)
    {
        return $this->service->approveRequest($user, $id);
    }
}
