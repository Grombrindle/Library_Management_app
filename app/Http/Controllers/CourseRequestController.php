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
        if (Auth::user()->privileges !== 0) abort(403);
        $teacher = Teacher::where('userName', Auth::user()->userName)->firstOrFail();
        $requests = $teacher->courseRequests()->latest()->get();
        return view('Teacher.CourseRequests', compact('requests'));
    }

    // TEACHER: Show form to submit new request
    public function create()
    {
        if (Auth::user()->privileges !== 0) abort(403);
        return view('Teacher.CourseRequestAdd');
    }

    // TEACHER: Store new request
    public function store(Request $request)
    {
        if (Auth::user()->privileges !== 0) abort(403);
        $teacher = Teacher::where('userName', Auth::user()->userName)->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'image' => 'nullable|string',
            'sources' => 'nullable|array',
            'price' => 'nullable|string',
        ]);
        $validated['teacher_id'] = $teacher->id;
        $validated['status'] = 'pending';
        $validated['sources'] = $request->input('sources', []);
        CourseRequest::create($validated);
        return redirect()->route('teacher.course_requests.index');
    }

    // TEACHER: Show a single request
    public function show($id)
    {
        if (Auth::user()->privileges !== 0) abort(403);
        $request = CourseRequest::findOrFail($id);
        if ($request->teacher_id !== Teacher::where('userName', Auth::user()->userName)->value('id')) abort(403);
        return view('Teacher.CourseRequestShow', compact('request'));
    }

    // TEACHER: Edit a rejected request
    public function edit($id)
    {
        if (Auth::user()->privileges !== 0) abort(403);
        $request = CourseRequest::findOrFail($id);
        if ($request->teacher_id !== Teacher::where('userName', Auth::user()->userName)->value('id')) abort(403);
        if ($request->status !== 'rejected') abort(403);
        return view('Teacher.CourseRequestEdit', compact('request'));
    }

    // TEACHER: Update and resubmit a rejected request
    public function update(Request $request, $id)
    {
        if (Auth::user()->privileges !== 0) abort(403);
        $courseRequest = CourseRequest::findOrFail($id);
        if ($courseRequest->teacher_id !== Teacher::where('userName', Auth::user()->userName)->value('id')) abort(403);
        if ($courseRequest->status !== 'rejected') abort(403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'image' => 'nullable|string',
            'sources' => 'nullable|array',
            'price' => 'nullable|string',
        ]);
        $validated['status'] = 'pending';
        $validated['sources'] = $request->input('sources', []);
        $courseRequest->update($validated);
        return redirect()->route('teacher.course_requests.index');
    }

    // ADMIN: List all requests
    public function adminIndex()
    {
        if (Auth::user()->privileges !== 2) abort(403);
        $requests = CourseRequest::latest()->get();
        return view('Admin.FullAdmin.CourseRequests', compact('requests'));
    }

    // ADMIN: Show a single request
    public function adminShow($id)
    {
        if (Auth::user()->privileges !== 2) abort(403);
        $request = CourseRequest::findOrFail($id);
        return view('Admin.FullAdmin.CourseRequestShow', compact('request'));
    }

    // ADMIN: Approve a request (creates a Course)
    public function approve($id)
    {
        if (Auth::user()->privileges !== 2) abort(403);
        $request = CourseRequest::findOrFail($id);
        if ($request->status !== 'pending') abort(403);
        // Create the course with all required fields
        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'lecturesCount' => 0,
            'subscriptions' => 0,
            'image' => $request->image ?? '',
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
        if (Auth::user()->privileges !== 2) abort(403);
        $courseRequest = CourseRequest::findOrFail($id);
        if ($courseRequest->status !== 'pending') abort(403);
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
