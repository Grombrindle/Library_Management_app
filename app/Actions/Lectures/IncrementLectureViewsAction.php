<?php

namespace App\Actions\Lectures;

use App\Models\Lecture;

class IncrementLectureViewsAction
{
    public function execute(int $lectureId): array
    {
        $lecture = Lecture::find($lectureId);

        if (!$lecture) {
            return [
                'success' => false,
                'message' => 'Lecture not found'
            ];
        }

        $lecture->increment('views');

        return [
            'success' => true,
            'views' => $lecture->views
        ];
    }
}