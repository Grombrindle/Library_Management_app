<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Lecture;

class WebProfileController extends Controller
{
    /**
     * Display the user's profile
     */
    public function show()
    {
        $user = Auth::user();
        $data = [];
        
        if ($user) {
            $data['user'] = $user;
            
            // Get enrolled courses
            $courses = $user->courses()->with('teacher')->take(3)->get();
            $data['courses'] = $courses;
            
            // Get favorite teachers
            $favoriteTeachers = $user->favoriteTeachers()->take(3)->get();
            $data['favoriteTeachers'] = $favoriteTeachers;
            
            // Check if user is a teacher
            $isTeacher = false;
            $teacherData = null;
            
            if ($user->privileges == 0) { // Assuming 0 = teacher privileges
                $isTeacher = true;
                $teacherData = Teacher::find($user->teacher_id);
            }
            
            $data['isTeacher'] = $isTeacher;
            $data['teacherData'] = $teacherData;
            
            // If user is a teacher, get their courses
            if ($isTeacher && $teacherData) {
                $teacherCourses = Course::where('teacher_id', $teacherData->id)->take(3)->get();
                $data['teacherCourses'] = $teacherCourses;
            }
        }
        
        return view('Website.webProfile', $data);
    }
} 