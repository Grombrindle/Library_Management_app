<?php

namespace App\Actions\Courses;

use App\Services\Courses\CourseManagementService;

class AddCourseAction
{
    protected CourseManagementService $service;

    public function __construct(CourseManagementService $service)
    {
        $this->service = $service;
    }

    public function execute($user, array $data, $file = null)
    {
        return $this->service->addCourse($user, $data, $file);
    }
}