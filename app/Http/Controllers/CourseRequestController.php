<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

use App\Services\CourseRequests\Teacher\CourseRequestManagementService as TeacherCourseRequestManagementService;
use App\Services\CourseRequests\Teacher\CourseRequestShowService as TeacherCourseRequestShowService;
use App\Services\CourseRequests\Admin\CourseRequestManagementService as AdminCourseRequestManagementService;
use App\Services\CourseRequests\Admin\CourseRequestShowService as AdminCourseRequestShowService;

class CourseRequestController extends Controller
{
    // TEACHER: List own requests
    public function index()
    {
        return app(TeacherCourseRequestShowService::class)->listTeacherRequests(Admin::find(Auth::id()));
    }

    // TEACHER: Show form to submit new request
    public function create()
    {
        return app(TeacherCourseRequestShowService::class)->showCreateForm(Admin::find(Auth::id()));
    }

    // TEACHER: Store new request
    public function store(Request $request)
    {
        return app(TeacherCourseRequestManagementService::class)->storeRequest(Admin::find(Auth::id()), $request->all());
    }

    // TEACHER: Show a single request
    public function show($id)
    {
        return app(TeacherCourseRequestShowService::class)->showTeacherRequest(Admin::find(Auth::id()), $id);
    }

    // TEACHER: Edit a rejected request
    public function edit($id)
    {
        return app(TeacherCourseRequestManagementService::class)->editRejectedRequest(Admin::find(Auth::id()), $id);
    }

    // TEACHER: Update and resubmit a rejected request
    public function update(Request $request, $id)
    {
        return app(TeacherCourseRequestManagementService::class)->updateRejectedRequest(Admin::find(Auth::id()), $id, $request->all());
    }

    // ADMIN: List all requests
    public function adminIndex()
    {
        return app(AdminCourseRequestShowService::class)->listAdminRequests(Admin::find(Auth::id()));
    }

    // ADMIN: Show a single request
    public function adminShow($id)
    {
        return app(AdminCourseRequestShowService::class)->showAdminRequest(Admin::find(Auth::id()), $id);
    }

    // ADMIN: Approve a request (creates a Course)
    public function approve($id)
    {
        return app(AdminCourseRequestManagementService::class)->approveRequest(Admin::find(Auth::id()), $id);
    }

    // ADMIN: Reject a request
    public function reject(Request $request, $id)
    {
        return app(AdminCourseRequestManagementService::class)->rejectRequest(Admin::find(Auth::id()), $id, $request->all());
    }
}
