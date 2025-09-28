<?php

namespace App\Actions\Lectures;

use App\Models\Lecture;

class GetLectureRatingsAction
{
    public function execute(int $id): array
    {
        $lecture = Lecture::with('ratings.user')->find($id);

        if (!$lecture) {
            return [
                'success' => false,
                'message' => 'Lecture not found'
            ];
        }

        return [
            'success' => true,
            'ratings' => $lecture->ratings
        ];
    }
}