<?php

namespace App\Actions\Courses;

use App\Services\Courses\CourseRatingService;

class FetchCourseRatingsAction
{
    protected CourseRatingService $service;

    public function __construct(CourseRatingService $service)
    {
        $this->service = $service;
    }

    public function execute($courseId)
    {
        return $this->service->fetchRatings($courseId);
    }
}