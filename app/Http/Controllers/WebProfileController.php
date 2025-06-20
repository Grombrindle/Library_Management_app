<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Lecture;
use Storage;

class WebProfileController extends Controller
{
    /**
     * Display the user's profile
     */
    // public function show()
    // {
    //     $user = Auth::user();
    //     $data = [];
        
    //     if ($user) {
    //         $data['user'] = $user;
            
    //         // Get enrolled courses
    //         $courses = $user->courses()->with('teacher')->take(3)->get();
    //         $data['courses'] = $courses;
            
    //         // Get favorite teachers
    //         $favoriteTeachers = $user->favoriteTeachers()->take(3)->get();
    //         $data['favoriteTeachers'] = $favoriteTeachers;
            
    //         // Check if user is a teacher
    //         $isTeacher = false;
    //         $teacherData = null;
            
    //         if ($user->privileges == 0) { // Assuming 0 = teacher privileges
    //             $isTeacher = true;
    //             $teacherData = Teacher::find($user->teacher_id);
    //         }
            
    //         $data['isTeacher'] = $isTeacher;
    //         $data['teacherData'] = $teacherData;
            
    //         // If user is a teacher, get their courses
    //         if ($isTeacher && $teacherData) {
    //             $teacherCourses = Course::where('teacher_id', $teacherData->id)->take(3)->get();
    //             $data['teacherCourses'] = $teacherCourses;
    //         }
    //     }
        
    //     return view('Website.webProfile', $data);
    // }
    // app/Http/Controllers/WebProfileController.php
    public function show()
    {
    $user = Auth::user();
    
    // Get course stats
    $totalCourses = $user->courses()->count();
    $completedCourses = $user->courses()->wherePivot('is_finished', true)->count();
    
    // Continue learning - latest 2 unfinished courses
    $continueLearning = $user->courses()
        ->wherePivot('is_finished', false)
        ->with('teacher')
        ->latest('subscriptions.updated_at')
        ->take(2)
        ->get();
    
    // Calculate progress for each course
    foreach ($continueLearning as $course) {
        $totalLectures = $course->lectures()->count();
        $completedLectures = $user->lectures()->where('course_id', $course->id)->count();
        $course->progress = $totalLectures > 0 ? round(($completedLectures / $totalLectures) * 100) : 0;
    }
    
    return view('Website.webProfile', [
        'user' => $user,
        'totalCourses' => $totalCourses,
        'completedCourses' => $completedCourses,
        'continueLearning' => $continueLearning,
    ]);
    }
public function edit()
{
    return view('Website.webEditProflie', ['user' => Auth::user()]);
}

public function update(Request $request)
{
    $user = Auth::user();
    
    $request->validate([
        'userName' => 'required|string|max:255|unique:users,userName,'.$user->id,
        'countryCode' => 'required|string|max:5',
        'number' => 'required|string|max:15',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $data = $request->only('userName', 'countryCode', 'number');

    if ($request->hasFile('avatar')) {
        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::delete('public/' . $user->avatar);
        }
        
        $path = $request->file('avatar')->store('avatars', 'public');
        $data['avatar'] = $path;
    }

    $user->update($data);

    return redirect()->route('web.profile')->with('success', 'Profile updated successfully!');
}

public function myCourses()
{
    $courses = Auth::user()->courses()
                ->with('teacher')
                ->withCount('lectures')
                ->orderBy('subscriptions.created_at', 'desc')
                ->paginate(6);
                
    return view('Website.webMyCourses', compact('courses'));
}

public function favorites()
{
    $user = Auth::user();
    
    return view('Website.webFavorites', [
        'favoriteCourses' => $user->favoriteCourses()->with('teacher')->get(),
        'favoriteTeachers' => $user->favoriteTeachers()->with('subjects')->get()
    ]);
}

public function toggleFavorite(Request $request, $type, $id)
{
    $user = Auth::user();
    
    if ($type === 'course') {
        $course = Course::findOrFail($id);
        $user->favoriteCourses()->toggle($course);
    } elseif ($type === 'teacher') {
        $teacher = Teacher::findOrFail($id);
        $user->favoriteTeachers()->toggle($teacher);
    }
    
    return response()->json(['success' => true]);
}
} 