<?php

namespace App\Actions\Courses;

use App\Services\Courses\CourseService;

class GetTeacherCoursesAction
{
    protected CourseService $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    public function execute($teacherId)
    {
        return $this->service->getTeacherCourses($teacherId);
    }
}