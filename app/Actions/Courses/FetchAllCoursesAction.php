<?php

namespace App\Actions\Courses;

use App\Services\Courses\CourseService;

class FetchAllCoursesAction
{
    protected CourseService $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    public function execute()
    {
        return $this->service->fetchAllCourses();
    }
}