<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseRequest;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Admin;

class CourseRequestController extends Controller
{
    // TEACHER: List own requests
    public function index()
    {
        if (Auth::user()->privileges !== 0)
            abort(403);
        $teacher = Teacher::where('userName', Auth::user()->userName)->firstOrFail();
        $requests = $teacher->courseRequests()->latest()->get();
        return view('Teacher.CourseRequests', compact('requests'));
    }

    // TEACHER: Show form to submit new request
    public function create()
    {
        if (Auth::user()->privileges !== 0)
            abort(403);
        $teacher = Teacher::where('userName', Auth::user()->userName)->firstOrFail();
        $subjects = $teacher->subjects;
        return view('Teacher.CourseRequestAdd', compact('subjects'));
    }

    // TEACHER: Store new request
    public function store(Request $request)
    {
        if (Auth::user()->privileges !== 0)
            abort(403);
        $teacher = Teacher::where('userName', Auth::user()->userName)->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'image' => 'nullable|string',
            'sources' => 'nullable|array',
            'price' => 'nullable|string',
            'lecturesCount' => 'nullable|integer',
            'subscriptions' => 'nullable|integer',
        ]);
        $validated['teacher_id'] = $teacher->id;
        $validated['status'] = 'pending';
        $validated['sources'] = $request->input('sources', []);
        $validated['lecturesCount'] = $request->input('lecturesCount');
        $validated['subscriptions'] = $request->input('subscriptions');
        CourseRequest::create($validated);
        return redirect()->route('teacher.course_requests.index')->with('success', 'Course request submitted successfully!');
    }

    // TEACHER: Show a single request
    public function show($id)
    {
        if (Auth::user()->privileges !== 0)
            abort(403);
        $request = CourseRequest::findOrFail($id);
        if ($request->teacher_id !== Teacher::where('userName', Auth::user()->userName)->value('id'))
            abort(403);
        return view('Teacher.CourseRequestShow', compact('request'));
    }

    // TEACHER: Edit a rejected request
    public function edit($id)
    {
        if (Auth::user()->privileges !== 0)
            abort(403);
        $request = CourseRequest::findOrFail($id);
        if ($request->teacher_id !== Teacher::where('userName', Auth::user()->userName)->value('id'))
            abort(403);
        if ($request->status !== 'rejected')
            abort(403);
        $teacher = Teacher::where('userName', Auth::user()->userName)->firstOrFail();
        $subjects = $teacher->subjects;
        return view('Teacher.CourseRequestEdit', compact('request', 'subjects'));
    }

    // TEACHER: Update and resubmit a rejected request
    public function update(Request $request, $id)
    {
        if (Auth::user()->privileges !== 0)
            abort(403);
        $courseRequest = CourseRequest::findOrFail($id);
        if ($courseRequest->teacher_id !== Teacher::where('userName', Auth::user()->userName)->value('id'))
            abort(403);
        if ($courseRequest->status !== 'rejected')
            abort(403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'image' => 'nullable|string',
            'sources' => 'nullable|array',
            'price' => 'nullable|string',
            'lecturesCount' => 'nullable|integer',
            'subscriptions' => 'nullable|integer',
        ]);
        $validated['status'] = 'pending';
        $validated['sources'] = $request->input('sources', []);
        $validated['lecturesCount'] = $request->input('lecturesCount');
        $validated['subscriptions'] = $request->input('subscriptions');
        $courseRequest->update($validated);
        return redirect()->route('teacher.course_requests.index')->with('success', 'Course request submitted successfully!');
    }

    // ADMIN: List all requests
    public function adminIndex()
    {
        if (Auth::user()->privileges !== 2)
            abort(403);
        $requests = CourseRequest::latest()->get();
        return view('Admin.FullAdmin.CourseRequests', compact('requests'));
    }

    // ADMIN: Show a single request
    public function adminShow($id)
    {
        if (Auth::user()->privileges !== 2)
            abort(403);
        $request = CourseRequest::findOrFail($id);
        return view('Admin.FullAdmin.CourseRequestShow', compact('request'));
    }

    // ADMIN: Approve a request (creates a Course)
    public function approve($id)
    {
        if (Auth::user()->privileges !== 2)
            abort(403);
        $request = CourseRequest::findOrFail($id);
        if ($request->status !== 'pending')
            abort(403);
        // Handle image: copy from CourseRequests to Courses
        $imagePath = 'Images/Courses/default.png';
        if ($request->image && (preg_match('/^Images\/CourseRequests\//', $request->image) || preg_match('/^Images\\CourseRequests\\/', $request->image))) {
            $sourcePath = public_path($request->image);
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
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'lecturesCount' => $request->lecturesCount ?? 0,
            'subscriptions' => $request->subscriptions ?? 0,
            'image' => $imagePath,
            'sources' => $request->sources ?? [],
            'price' => $request->price ?? 0,
        ]);
        $request->update([
            'status' => 'approved',
            'admin_id' => Auth::user()->id,
            'course_id' => $course->id,
            'rejection_reason' => null,
        ]);
        return redirect()->route('admin.course_requests.index');
    }

    // ADMIN: Reject a request
    public function reject(Request $request, $id)
    {
        if (Auth::user()->privileges !== 2)
            abort(403);
        $courseRequest = CourseRequest::findOrFail($id);
        if ($courseRequest->status !== 'pending')
            abort(403);
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string',
        ]);
        $courseRequest->update([
            'status' => 'rejected',
            'admin_id' => Auth::user()->id,
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);
        return redirect()->route('admin.course_requests.index');
    }
}
