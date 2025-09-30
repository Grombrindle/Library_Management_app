<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\CourseRequests\Teacher\CourseRequestManagementService as TeacherCourseRequestManagementService;
use App\Services\CourseRequests\Teacher\CourseRequestShowService as TeacherCourseRequestShowService;
use App\Services\CourseRequests\Admin\CourseRequestManagementService as AdminCourseRequestManagementService;
use App\Services\CourseRequests\Admin\CourseRequestShowService as AdminCourseRequestShowService;

class CourseRequestController extends Controller
{
    // TEACHER: List own requests
    public function index()
    {
        return app(TeacherCourseRequestShowService::class)->listTeacherRequests(Auth::user());
    }

    // TEACHER: Show form to submit new request
    public function create()
    {
        return app(TeacherCourseRequestShowService::class)->showCreateForm(Auth::user());
    }

    // TEACHER: Store new request
    public function store(Request $request)
    {
        return app(TeacherCourseRequestManagementService::class)->storeRequest(Auth::user(), $request->all());
    }

    // TEACHER: Show a single request
    public function show($id)
    {
        return app(TeacherCourseRequestShowService::class)->showTeacherRequest(Auth::user(), $id);
    }

    // TEACHER: Edit a rejected request
    public function edit($id)
    {
        return app(TeacherCourseRequestManagementService::class)->editRejectedRequest(Auth::user(), $id);
    }

    // TEACHER: Update and resubmit a rejected request
    public function update(Request $request, $id)
    {
        return app(TeacherCourseRequestManagementService::class)->updateRejectedRequest(Auth::user(), $id, $request->all());
    }

    // ADMIN: List all requests
    public function adminIndex()
    {
        return app(AdminCourseRequestShowService::class)->listAdminRequests(Auth::user());
    }

    // ADMIN: Show a single request
    public function adminShow($id)
    {
        return app(AdminCourseRequestShowService::class)->showAdminRequest(Auth::user(), $id);
    }

    // ADMIN: Approve a request (creates a Course)
    public function approve($id)
    {
        return app(AdminCourseRequestManagementService::class)->approveRequest(Auth::user(), $id);
    }

    // ADMIN: Reject a request
    public function reject(Request $request, $id)
    {
        return app(AdminCourseRequestManagementService::class)->rejectRequest(Auth::user(), $id, $request->all());
    }
}
