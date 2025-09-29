<?php

namespace App\Actions\CourseRequests\Admin;

use App\Services\CourseRequestService;
use App\Models\Admin;

class RejectRequestAction
{
    public function __construct(private CourseRequestService $service) {}

    public function execute(Admin $user, int $id, array $data)
    {
        return $this->service->rejectRequest($user, $id, $data);
    }
}
