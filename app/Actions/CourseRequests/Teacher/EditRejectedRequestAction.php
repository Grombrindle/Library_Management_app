<?php

namespace App\Actions\CourseRequests\Teacher;

use App\Services\CourseRequests\CourseRequestService;
use App\Models\Admin;

class EditRejectedRequestAction
{
    public function __construct(private CourseRequestService $service) {}

    public function execute(Admin $user, int $id)
    {
        return $this->service->editRejectedRequest($user, $id);
    }
}
