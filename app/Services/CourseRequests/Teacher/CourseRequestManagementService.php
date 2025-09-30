<?php

namespace App\Services\CourseRequests\Teacher;

use App\Models\CourseRequest;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;

class CourseRequestManagementService
{

    /*
    |--------------------------------------------------------------------------
    | Teacher Methods
    |--------------------------------------------------------------------------
    */

    public function storeRequest(Admin $user, array $data)
    {
        if ($user->privileges !== 0) {
            abort(403);
        }

        $teacher = Teacher::where('userName', $user->userName)->firstOrFail();

        $validated = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'image' => 'nullable|string',
            'sources' => 'nullable|array',
            'price' => 'nullable|string',
            'lecturesCount' => 'nullable|integer',
            'subscriptions' => 'nullable|integer',
        ])->validate();

        $validated['teacher_id'] = $teacher->id;
        $validated['status'] = 'pending';
        $validated['sources'] = $data['sources'] ?? [];
        $validated['lecturesCount'] = $data['lecturesCount'] ?? null;
        $validated['subscriptions'] = $data['subscriptions'] ?? null;

        CourseRequest::create($validated);

        return redirect()->route('teacher.course_requests.index')
            ->with('success', 'Course request submitted successfully!');
    }


    public function editRejectedRequest(Admin $user, int $id)
    {
        if ($user->privileges !== 0) {
            abort(403);
        }

        $courseRequest = CourseRequest::findOrFail($id);
        $teacherId = Teacher::where('userName', $user->userName)->value('id');

        if ($courseRequest->teacher_id !== $teacherId) {
            abort(403);
        }

        if ($courseRequest->status !== 'rejected') {
            abort(403);
        }

        $teacher = Teacher::where('userName', $user->userName)->firstOrFail();
        $subjects = $teacher->subjects;

        return view('Teacher.CourseRequestEdit', compact('request', 'subjects'))
            ->with('request', $courseRequest);
    }

    public function updateRejectedRequest(Admin $user, int $id, array $data)
    {
        if ($user->privileges !== 0) {
            abort(403);
        }

        $courseRequest = CourseRequest::findOrFail($id);
        $teacherId = Teacher::where('userName', $user->userName)->value('id');

        if ($courseRequest->teacher_id !== $teacherId) {
            abort(403);
        }

        if ($courseRequest->status !== 'rejected') {
            abort(403);
        }
        $validated = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'image' => 'nullable|string',
            'sources' => 'nullable|array',
            'price' => 'nullable|string',
            'lecturesCount' => 'nullable|integer',
            'subscriptions' => 'nullable|integer',
        ])->validate();

        $validated['status'] = 'pending';
        $validated['sources'] = $data['sources'] ?? [];
        $validated['lecturesCount'] = $data['lecturesCount'] ?? null;
        $validated['subscriptions'] = $data['subscriptions'] ?? null;

        $courseRequest->update($validated);

        return redirect()->route('teacher.course_requests.index')
            ->with('success', 'Course request submitted successfully!');
    }
}
