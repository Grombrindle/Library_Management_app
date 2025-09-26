<?php

namespace App\Actions\Courses;

use App\Services\Courses\CourseService;
use Illuminate\Support\Facades\Auth;

class FetchHomePageCoursesAction
{
    protected CourseService $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    public function execute($user = null)
    {
        $user = $user ?? Auth::user();
        return $this->service->fetchHomePageCourses($user);
    }
}