<?php

namespace App\Services;

use App\Models\Lecture;
use App\Models\LectureRating;
use Illuminate\Support\Facades\Auth;

class LectureRatingService
{

    public function getLectureRatings(int $lectureId)
    {
        $ratings = Lecture::find($lectureId)->ratings()
            ->where('isHidden', false)
            ->whereNotNull('review')
            ->get();


        if (!$ratings) {
            return [];
        }

        return response()->json([
            'success' => true,
            'featuredRatings' => $ratings
        ]);
    }

    public function getLectureFeaturedRatings(int $lectureId)
    {
        $lecture = Lecture::find($lectureId);

        if ($lecture)
            return response()->json([
                'success' => true,
                'featuredRatings' => $lecture->getFeaturedRatingsAttribute(),
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
            'featuredRatings' => $lecture->featured_ratings,
            'rating_breakdown' => $lecture->rating_breakdown,
            'lectureRating' => $lecture->rating
        ];
    }
}
