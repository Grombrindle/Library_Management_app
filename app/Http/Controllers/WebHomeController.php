<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class WebHomeController extends Controller
{
    /**
     * Display the home page with statistics.
     */
    public function index()
    {
        $stats = [
            'students' => User::where('privileges', 3)->count(),
            'courses' => Course::count(),
            'teachers' => Teacher::count(),
        ];
        
        $courses = Course::with('teacher', 'subject')->latest()->take(3)->get();
        $teachers = Teacher::withCount('courses')->latest()->take(3)->get();

        return view('Website.webHome', compact('stats', 'teachers', 'courses'));
    }

}
