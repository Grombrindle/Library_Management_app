<?php

namespace App\Actions\Lectures;

use App\Models\Lecture;

class IncrementLectureViewsAction
{
    public function execute(int $lectureId): ?Lecture
    {
        $lecture = Lecture::find($lectureId);

        if ($lecture) {
            $lecture->increment('views');
        }

        return $lecture;
    }
}