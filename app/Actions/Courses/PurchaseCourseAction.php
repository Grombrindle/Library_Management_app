<?php

namespace App\Actions\Courses;

use App\Services\Courses\CoursePurchaseService;

class PurchaseCourseAction
{
    protected CoursePurchaseService $service;

    public function __construct(CoursePurchaseService $service)
    {
        $this->service = $service;
    }

    public function execute($user, $courseId)
    {
        return $this->service->purchaseCourse($user, $courseId);
    }
}