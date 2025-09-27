<?php

namespace App\Actions\Lectures;

use App\Models\Lecture;

class FetchLectureQuizQuestionsAction
{
    public function execute(int $lectureId)
    {
        $lecture = Lecture::find($lectureId);

        if (!$lecture) {
            return [];
        }

        return $lecture->quiz;
    }
}