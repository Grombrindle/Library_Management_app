<?php

namespace App\Actions\Courses;

use App\Services\Courses\CourseService;

class FetchRecentCoursesAction
{
    protected CourseService $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    public function execute()
    {
        return $this->service->fetchAllRecentCourses();
    }
}