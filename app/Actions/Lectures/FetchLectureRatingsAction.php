<?php

namespace App\Actions\Lectures;

use App\Models\Lecture;

class FetchLectureRatingsAction
{
    public function execute(int $lectureId): array
    {
        $lecture = Lecture::with('ratings')->find($lectureId);

        if (!$lecture) {
            return [];
        }

        return $lecture->ratings->toArray();
    }
}