<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Subject;
use Carbon\Carbon;

class WebCoursesController extends Controller
{
    /**
     * Display the courses list page
     */
    public function index(Request $request)
    {
        // Get search term and category from request
        $search = $request->input('search');
        $category = $request->input('category');

        // Base query
        $query = Course::with(['teacher', 'subject', 'ratings']);

        // Apply search filter if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('teacher', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by category (Scientific/Literary) if provided
        if ($category && $category !== 'all') {
            $isScientific = ($category === 'scientific');
            $query->whereHas('subject', function ($q) use ($isScientific) {
                $q->where('literaryOrScientific', $isScientific);
            });
        }

        // Get courses with pagination
        $courses = $query->orderBy('created_at', 'desc')->paginate(9);
        
        // Get featured course (most rated this month)
        $featuredCourse = Course::withCount(['ratings' => function($query) {
            $query->where('created_at', '>=', Carbon::now()->subMonth());
        }])
        ->orderBy('ratings_count', 'desc')
        ->with(['teacher', 'subject'])
        ->first();

        // Get subjects for filtering
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