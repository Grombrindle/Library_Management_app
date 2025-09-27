<?php

namespace App\Actions\CourseRequests\Teacher;

use App\Services\CourseRequests\CourseRequestService;
use App\Models\Admin;

class UpdateRejectedRequestAction
{
    public function __construct(private CourseRequestService $service) {}

    public function execute(Admin $user, int $id, array $data)
    {
        return $this->service->updateRejectedRequest($user, $id, $data);
    }
}
