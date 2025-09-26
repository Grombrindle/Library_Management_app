<?php

namespace App\Actions\Courses;

use App\Services\Courses\CourseManagementService;

class EditCourseAction
{
    protected CourseManagementService $service;

    public function __construct(CourseManagementService $service)
    {
        $this->service = $service;
    }

    public function execute($courseId, array $data, $file = null)
    {
        return $this->service->editCourse($courseId, $data, $file);
    }
}