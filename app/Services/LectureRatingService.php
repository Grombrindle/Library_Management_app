<?php

namespace App\Services;

use App\Models\Lecture;
use App\Models\LectureRating;
use Illuminate\Support\Facades\Auth;

class LectureRatingService
{

    public function getLectureRatings(int $lectureId)
    {
        $lecture = Lecture::with('ratings')->find($lectureId);

        if (!$lecture) {
            return [];
        }

        return response()->json([
            'success' => true,
            'ratings' => $lecture->ratings
        ]);
    }

    public function getLectureFeaturedRatings(int $lectureId)
    {
        $lecture = Lecture::find($lectureId);

        if ($lecture)
            return response()->json([
                'success' => true,
                'FeaturedRatings' => $lecture->getFeaturedRatingsAttribute(),
            ]);

        return response()->json([
            'success' => false,
            'message' => 'Lecture not found'
        ], 404);

    }

    public function rateLecture(int $lectureId, int $rating, ?string $review = null): array
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
            'rating' => $lectureRating->rating,
            'review' => $lectureRating->review,
        ];
    }
}