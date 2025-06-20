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
            'students' => User::where('privileges', 1)->count(),
            'courses' => Course::count(),
            'teachers' => Teacher::count(),
        ];

        return view('Website.webHome', ['stats' => $stats]);
    }
}
