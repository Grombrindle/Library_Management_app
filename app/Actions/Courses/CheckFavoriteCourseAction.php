<?php

namespace App\Actions\Courses;

use Illuminate\Support\Facades\Auth;

class CheckFavoriteCourseAction
{
    public function execute($courseId)
    {
        $user = Auth::user();

        $isFavorited = $user->favoriteCourses()->where('course_id', $courseId)->exists();
        return response()->json(['success' => true, 'is_favorited' => $isFavorited]);
    }
}