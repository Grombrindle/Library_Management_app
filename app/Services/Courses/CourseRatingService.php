<?php

namespace App\Services\Courses;

use Illuminate\Support\Facades\DB;

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

    public function rateCourse($user, $courseId, array $data)
    {
        $exists = DB::table('course_rating')->updateOrInsert(
            [
                'user_id' => $user->id,
                'course_id' => $courseId
            ],
            [
                'rating' => $data['rating'] ?? null,
                'review' => $data['review'] ?? null,
                'updated_at' => now()
            ]
        );

        return response()->json(['success' => true, 'message' => 'Rating saved successfully']);
    }
}