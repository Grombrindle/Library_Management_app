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
    public function getTeacherCourses($teacherId)
    {
        $teacher = Teacher::find($teacherId);
        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Teacher not found'], 404);
        }

        $courses = $teacher->courses()
            ->with(['subject:id,name,literaryOrScientific'])
            ->get()
            ->map(fn($course) => tap($course, fn($c) => $c->sources = json_decode($c->sources, true)));

        return response()->json([
            'success' => true,
            'courses' => $courses,
            'teacher' => ['id' => $teacher->id, 'name' => $teacher->name]
        ]);
    }

    public function fetchCourse($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['success' => false, 'reason' => 'Course Not Found'], 404);
        }

        $course->sources = json_decode($course->sources, true);
        return response()->json(['success' => true, 'course' => $course]);
    }

    public function fetchAllCourses()
    {
        $courses = Course::all()->map(function ($course) {
            $course->rating = DB::table('course_rating')->where('course_id', $course->id)->avg('rating') ?? null;
            $course->sources = is_string($course->sources) ? json_decode($course->sources, true) : ($course->sources ?? []);
            return $course;
        });

        return response()->json(['courses' => $courses]);
    }

    public function fetchAllRecentCourses()
    {
        $courses = Course::withAvg('ratings', 'rating')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($course) => tap($course, fn($c) => $c->sources = is_string($c->sources) ? json_decode($c->sources, true) : ($c->sources ?? [])));

        return response()->json(['courses' => $courses]);
    }

    public function fetchAllRatedCourses()
    {
        $courses = Course::withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->get()
            ->map(fn($course) => tap($course, fn($c) => $c->sources = is_string($c->sources) ? json_decode($c->sources, true) : ($c->sources ?? [])));

        return response()->json(['courses' => $courses]);
    }

    public function fetchAllSubscribedCourses()
    {
        $courses = Course::withCount('users')
            ->orderByDesc('users_count')
            ->get()
            ->map(fn($course) => tap($course, fn($c) => $c->sources = is_string($c->sources) ? json_decode($c->sources, true) : ($c->sources ?? [])));

        return response()->json(['courses' => $courses]);
    }

    public function fetchAllRecommendedCourses()
    {
        $courses = Course::withCount(['users', 'ratings', 'lectures'])
            ->withAvg('ratings', 'rating')
            ->orderByDesc(DB::raw('
                (
                    (COALESCE(ratings_avg_rating, 0) * 0.5) +
                    (ratings_count * 0.2) +
                    (users_count * 0.2) +
                    (lectures_count * 0.1)
                ) *
                (1 + (COALESCE(ratings_avg_rating, 0) / 5))
            '))
            ->get()
            ->map(fn($course) => tap($course, fn($c) => $c->sources = is_string($c->sources) ? json_decode($c->sources, true) : ($c->sources ?? [])));

        return response()->json(['courses' => $courses]);
    }

    public function fetchAllUserSubscribedCourses($user)
    {
        $courses = $user->courses()
            ->withCount('users')
            ->withAvg('ratings', 'rating')
            ->get()
            ->map(fn($course) => tap($course, fn($c) => $c->sources = is_string($c->sources) ? json_decode($c->sources, true) : ($c->sources ?? [])));

        return response()->json(['courses' => $courses]);
    }

    public function fetchCourseTeachers($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(['success' => false, 'reason' => 'Course Not Found'], 404);
        }
        $course->sources = json_decode($course->sources, true);
        $teachers = $course->teacher ?? null;
        return response()->json(['success' => true, 'teachers' => $teachers, 'course' => $course]);
    }

    public function fetchHomePageCourses($user)
    {
        $cacheKey = 'homepage_courses_' . ($user->id ?? 'guest');
        $cacheDuration = 180; // 3 minutes

        return Cache::remember($cacheKey, $cacheDuration, function () use ($user) {
            $recommended = Course::withCount(['users', 'ratings', 'lectures'])
                ->withAvg('ratings', 'rating')
                ->orderByDesc(DB::raw('
                    (
                        (COALESCE(ratings_avg_rating, 0) * 0.5) +
                        (ratings_count * 0.2) +
                        (users_count * 0.2) +
                        (lectures_count * 0.1)
                    ) *
                    (1 + (COALESCE(ratings_avg_rating, 0) / 5))
                '))
                ->limit(7)
                ->get()
                ->map(fn($c) => tap($c, fn($x) => $x->sources = json_decode($x->sources, true)));

            $topRated = Course::withAvg('ratings', 'rating')
                ->orderByDesc('ratings_avg_rating')
                ->limit(7)
                ->get()
                ->map(fn($c) => tap($c, fn($x) => $x->sources = json_decode($x->sources, true)));

            $mostSubscribed = Course::withCount('users')
                ->orderByDesc('users_count')
                ->limit(7)
                ->get()
                ->map(fn($c) => tap($c, fn($x) => $x->sources = json_decode($x->sources, true)));

            $recent = Course::orderByDesc('created_at')
                ->limit(7)
                ->get()
                ->map(fn($c) => tap($c, fn($x) => $x->sources = is_string($x->sources) ? json_decode($x->sources, true) : ($x->sources ?? [])));

            $userSubscribed = null;
            if ($user) {
                $userSubscribed = $user->courses()->limit(7)->get()
                    ->map(fn($c) => tap($c, fn($x) => $x->sources = json_decode($x->sources, true)));
            }

            return response()->json([
                'success' => true,
                'user' => User::select(['isBanned', 'hasWarning', 'counter', 'message'])
                    ->where('id', $user->id)->get()->map(fn($u) => [
                        'isBanned' => $u->isBanned,
                        'hasWarning' => $u->hasWarning,
                        'counter' => $u->counter,
                        'message' => $u->message
                    ]),
                'recommended' => $recommended,
                'top_rated' => $topRated,
                'most_subscribed' => $mostSubscribed,
                'recent' => $recent,
                'user_subscribed' => $userSubscribed,
            ]);
        });
    }

    public function checkFavoriteCourse($user, $courseId)
    {
        $isFavorited = $user->favoriteCourses()->where('course_id', $courseId)->exists();
        return response()->json(['is_favorited' => $isFavorited]);
    }
}