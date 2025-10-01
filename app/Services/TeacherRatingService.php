<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeacherRatingService {

    public function fetchRatings($id)
    {
        $ratings = DB::table('teacher_ratings')->where('teacher_id', $id)->where('isHidden', false)->get();
        return response()->json([
            'ratings' => $ratings
        ]);
    }

    public function getFeaturedRatings(int $courseId)
    {
        $course = Course::find($courseId);

        if ($course)
            return response()->json([
                'success' => true,
                'FeaturedRatings' => $course->getFeaturedRatingsAttribute(),
            ]);

        return response()->json([
            'success' => false,
            'message' => 'Course not found'
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