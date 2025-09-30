<?php

namespace App\Services\Courses;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CourseService
{
    public function checkFavoriteCourse($user, $courseId)
    {
        $isFavorited = $user->favoriteCourses()->where('course_id', $courseId)->exists();
        return response()->json(['is_favorited' => $isFavorited]);
    }
}