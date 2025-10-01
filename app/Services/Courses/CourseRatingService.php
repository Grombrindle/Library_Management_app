<?php

namespace App\Services\Courses;

use Illuminate\Support\Facades\DB;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseRatingService
{
    public function fetchRatings($courseId)
    {
        $ratings = DB::table('course_rating')
            ->where('course_id', $courseId)
            ->where('isHidden', false)
            ->get();

        return response()->json(['ratings' => $ratings]);
    }

    public function fetchFeaturedRatings($id)
    {

        $course = Course::find($id);

        return response()->json([
            'success' => true,
            'FeaturedRatings' => $course->getFeaturedRatingsAttribute(),
        ]);

    }

    public function rateCourse($user, $courseId, array $data)
    {
        $course = Course::find($courseId);

        $rating = $course->ratings()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['rating' => $data['rating'], 'review' => $data['review']]
        );

        return response()->json([
            'success' => true,
            'rating' => $rating->rating,
            'review' => $rating->review,
        ]);
    }
}