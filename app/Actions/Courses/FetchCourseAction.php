<?php

namespace App\Actions\Courses;

use App\Services\Courses\CourseService;

class FetchCourseAction
{
    protected CourseService $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    public function execute($courseId)
    {
        return $this->service->fetchCourse($courseId);
    }
}