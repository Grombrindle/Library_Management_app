<?php

namespace App\Services\Ratings;

use App\Models\Teacher;

class RatingService
{
    public function computeTeacherAverage(Teacher $teacher): ?float
    {
        $avg = $teacher->ratings()->avg('rating');
        return $avg !== null ? round((float)$avg, 2) : null;
    }

    public function computeTeacherRatingsCount(Teacher $teacher): int
    {
        return (int) $teacher->ratings()->count();
    }
}


