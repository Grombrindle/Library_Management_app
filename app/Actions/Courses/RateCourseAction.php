<?php

namespace App\Actions\Courses;

use App\Services\Courses\CourseRatingService;

class RateCourseAction
{
    protected CourseRatingService $service;

    public function __construct(CourseRatingService $service)
    {
        $this->service = $service;
    }

    public function execute($user, $courseId, array $data)
    {
        return $this->service->rateCourse($user, $courseId, $data);
    }
}