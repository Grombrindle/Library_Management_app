<?php

namespace App\Actions\Lectures;

use App\Models\Lecture;
use App\Models\LectureRating;
use Illuminate\Support\Facades\Auth;

class RateLectureAction
{
    public function execute(int $lectureId, int $rating, ?string $review = null): array
    {
        $lecture = Lecture::find($lectureId);

        if (!$lecture) {
            return [
                'success' => false,
                'message' => 'Lecture not found'
            ];
        }

        $lectureRating = LectureRating::updateOrCreate(
            [
                'lecture_id' => $lecture->id,
                'user_id' => Auth::id()
            ],
            [
                'rating' => $rating,
                'review' => $review
            ]
        );

        return [
            'success' => true,
            'lecture_rating' => $lectureRating
        ];
    }
}