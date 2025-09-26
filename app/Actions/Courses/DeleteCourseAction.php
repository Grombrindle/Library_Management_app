<?php

namespace App\Actions\Courses;

use App\Services\Courses\CourseManagementService;

class DeleteCourseAction
{
    protected CourseManagementService $service;

    public function __construct(CourseManagementService $service)
    {
        $this->service = $service;
    }

    public function execute($courseId)
    {
        return $this->service->deleteCourse($courseId);
    }
}