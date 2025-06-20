<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Subject;
use Carbon\Carbon;

class WebCoursesController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    $category = $request->input('category');

    // This query for the main grid is fine
    $courses = Course::query()
        ->with(['teacher', 'subject', 'lectures', 'ratings'])
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('teacher', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
            });
        })
        ->when($category && $category !== 'all', function ($query) use ($category) {
            $isScientific = ($category === 'scientific');
            $query->whereHas('subject', function ($q) use ($isScientific) {
                $q->where('literaryOrScientific', $isScientific);
            });
        })
        ->withCount('users as subscriptions_count')
        ->orderBy('created_at', 'desc')
        ->paginate(12);

    // ===== CORRECTED AND VERIFIED LOGIC FOR FEATURED COURSE =====
    $featuredCourse = Course::query()
        ->with(['teacher', 'subject', 'lectures'])       // Eager load relationships for the view
        ->withCount('users as subscriptions_count')     // Get student count for the modal
        ->withCount('ratings')                          // Get the total number of ratings
        ->withAvg('ratings', 'rating')                  // Calculate the average rating and add it as `ratings_avg_rating`
        ->has('ratings')                                // Only select courses with one or more ratings
        ->orderByDesc('ratings_avg_rating')             // **ORDER BY THE CORRECT CALCULATED AVERAGE**
        ->orderByDesc('ratings_count')                  // Then, order by the number of ratings as a tie-breaker
        ->first();

    // Fallback if no courses have been rated yet
    if (!$featuredCourse) {
        $featuredCourse = Course::query()
            ->with(['teacher', 'subject', 'lectures', 'ratings'])
            ->withCount('users as subscriptions_count')
            ->latest()
            ->first();
    }

    $subjects = Subject::orderBy('name')->get();

    return view('Website.webCourses', [
        'courses' => $courses,
        'featuredCourse' => $featuredCourse,
        'subjects' => $subjects,
        'search' => $search,
        'category' => $category
    ]);
}
}