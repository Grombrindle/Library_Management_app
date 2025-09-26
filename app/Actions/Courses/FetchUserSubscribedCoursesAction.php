<?php

namespace App\Actions\Courses;

use App\Services\Courses\CourseService;
use Illuminate\Support\Facades\Auth;

class FetchUserSubscribedCoursesAction
{
    protected CourseService $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    public function execute()
    {
        $user = Auth::user();
        return $this->service->fetchAllUserSubscribedCourses($user);
    }
}