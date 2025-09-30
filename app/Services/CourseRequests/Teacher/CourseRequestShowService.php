<?php

namespace App\Services\CourseRequests\Teacher;

use App\Models\Admin;
use App\Models\Teacher;
use App\Models\CourseRequest;

class CourseRequestShowService
{

    /*
    |--------------------------------------------------------------------------
    | Teacher Methods
    |--------------------------------------------------------------------------
    */

    public function listTeacherRequests(Admin $user)
    {
        if ($user->privileges !== 0) {
            abort(403);
        }

        $teacher = Teacher::where('userName', $user->userName)->firstOrFail();
        $requests = $teacher->courseRequests()->latest()->get();

        return view('Teacher.CourseRequests', compact('requests'));
    }

    public function showCreateForm(Admin $user)
    {
        if ($user->privileges !== 0) {
            abort(403);
        }

        $teacher = Teacher::where('userName', $user->userName)->firstOrFail();
        $subjects = $teacher->subjects;

        return view('Teacher.CourseRequestAdd', compact('subjects'));
    }

    public function showTeacherRequest(Admin $user, int $id)
    {
        if ($user->privileges !== 0) {
            abort(403);
        }

        $courseRequest = CourseRequest::findOrFail($id);
        $teacherId = Teacher::where('userName', $user->userName)->value('id');

        if ($courseRequest->teacher_id !== $teacherId) {
            abort(403);
        }

        return view('Teacher.CourseRequestShow', ['request' => $courseRequest]);
    }
}