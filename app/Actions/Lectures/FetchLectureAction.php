<?php

namespace App\Actions\Lectures;

use App\Models\Lecture;

class FetchLectureAction
{
    public function execute(int $lectureId): ?Lecture
    {
        return Lecture::with(['ratings'])->find($lectureId);
    }
}