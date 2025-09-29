<?php

namespace App\Services;

use App\Models\CourseRequest;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;

class CourseRequestService
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

    /*
    |--------------------------------------------------------------------------
    | Admin Methods
    |--------------------------------------------------------------------------
    */

    public function listAdminRequests(Admin $user)
    {
        if ($user->privileges !== 2) {
            abort(403);
        }

        $requests = CourseRequest::orderByRaw(
            "CASE WHEN status = 'pending' THEN 0 WHEN status = 'rejected' THEN 1 ELSE 2 END"
        )
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('Admin.FullAdmin.CourseRequests', compact('requests'));
    }

    public function showAdminRequest(Admin $user, int $id)
    {
        if ($user->privileges !== 2) {
            abort(403);
        }

        $courseRequest = CourseRequest::findOrFail($id);

        return view('Admin.FullAdmin.CourseRequestShow', ['request' => $courseRequest]);
    }

    public function approveRequest(Admin $user, int $id)
    {
        if ($user->privileges !== 2) {
            abort(403);
        }

        $courseRequest = CourseRequest::findOrFail($id);
        if ($courseRequest->status !== 'pending') {
            abort(403);
        }

        // Handle image: copy from CourseRequests to Courses
        $imagePath = 'Images/Courses/default.png';
        if ($courseRequest->image && preg_match('#^Images[\\\\/]CourseRequests[\\\\/]#', $courseRequest->image)) {
            $sourcePath = public_path($courseRequest->image);
            if (file_exists($sourcePath)) {
                $directory = 'Images/Courses';
                $filename = uniqid() . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
                if (!file_exists(public_path($directory))) {
                    mkdir(public_path($directory), 0755, true);
                }
                $destPath = public_path($directory . '/' . $filename);
                copy($sourcePath, $destPath);
                $imagePath = $directory . '/' . $filename;
            }
        }

        // Create the course with all required fields
        $course = Course::create([
            'name' => $courseRequest->name,
            'description' => $courseRequest->description,
            'teacher_id' => $courseRequest->teacher_id,
            'subject_id' => $courseRequest->subject_id,
            'lecturesCount' => $courseRequest->lecturesCount ?? 0,
            'subscriptions' => $courseRequest->subscriptions ?? 0,
            'image' => $imagePath,
            'requirements' => $courseRequest->requirements,
            'sources' => $courseRequest->sources ?? [],
            'price' => $courseRequest->price ?? 0,
        ]);

        $courseRequest->update([
            'status' => 'approved',
            'admin_id' => $user->id,
            'course_id' => $course->id,
            'rejection_reason' => null,
        ]);

        return redirect()->route('admin.course_requests.index');
    }

    public function rejectRequest(Admin $user, int $id, array $data)
    {
        if ($user->privileges !== 2) {
            abort(403);
        }
        $courseRequest = CourseRequest::findOrFail($id);
        if ($courseRequest->status !== 'pending') {
            abort(403);
        }

        $validated = Validator::make($data, [
            'rejection_reason' => 'nullable|string',
        ])->validate();

        $courseRequest->update([
            'status' => 'rejected',
            'admin_id' => $user->id,
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);

        return redirect()->route('admin.course_requests.index');
    }
}
