<?php

namespace App\Actions\Lectures;

use App\Models\LectureRating;
use Illuminate\Support\Facades\Auth;

class RateLectureAction
{
    public function execute(int $lectureId, int $rating, ?String $review): LectureRating
    {
        $user = Auth::user();

        return LectureRating::updateOrCreate(
            [
                'lecture_id' => $lectureId,
                'user_id'    => $user->id,
            ],
            [
                'rating' => $rating,
                'review' => $review
            ]
        );
    }
}