<?php

namespace App\Services;

use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeacherRatingService
{

    public function fetchRatings($teacherId)
    {
        $ratings = Teacher::find($teacherId)->ratings()
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

    public function getFeaturedRatings(int $teacherId)
    {
        $teacher = Teacher::find($teacherId);

        if ($teacher)
            return response()->json([
                'success' => true,
                'FeaturedRatings' => $teacher->getFeaturedRatingsAttribute(),
            ]);

        return response()->json([
            'success' => false,
            'message' => 'Teacher not found'
        ], 404);

    }

    public function rate($id, $rating, $review = null)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'reason' => "Teacher Not Found"
            ], 404);
        }

        $rating = $teacher->ratings()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['rating' => $rating, 'review' => $review]
        );

        return response()->json([
            'success' => true,
            'rating' => $rating->rating,
            'review' => $rating->review,
        ]);
    }
}