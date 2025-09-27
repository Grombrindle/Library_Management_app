<?php

namespace App\Actions\CourseRequests\Admin;

use App\Services\CourseRequests\CourseRequestService;
use App\Models\Admin;

class ShowRequestAction
{
    public function __construct(private CourseRequestService $service) {}

    public function execute(Admin $user, int $id)
    {
        return $this->service->showAdminRequest($user, $id);
    }
}
