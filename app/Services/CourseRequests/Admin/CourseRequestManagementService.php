<?php

namespace App\Services\CourseRequests\Admin;

use App\Models\CourseRequest;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;

class CourseRequestManagementService
{
    /*
    |--------------------------------------------------------------------------
    | Admin Methods
    |--------------------------------------------------------------------------
    */


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
