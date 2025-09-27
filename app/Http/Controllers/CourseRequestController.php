<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Actions\CourseRequests\Teacher\{
    ListOwnRequestsAction,
    CreateRequestFormAction,
    StoreCourseRequestAction,
    ShowRequestAction as TeacherShowRequestAction,
    EditRejectedRequestAction,
    UpdateRejectedRequestAction
};
use App\Actions\CourseRequests\Admin\{
    ListAllRequestsAction,
    ShowRequestAction as AdminShowRequestAction,
    ApproveRequestAction,
    RejectRequestAction
};

class CourseRequestController extends Controller
{
    // TEACHER: List own requests
    public function index()
    {
        return app(ListOwnRequestsAction::class)->execute(Auth::user());
    }

    // TEACHER: Show form to submit new request
    public function create()
    {
        return app(CreateRequestFormAction::class)->execute(Auth::user());
    }

    // TEACHER: Store new request
    public function store(Request $request)
    {
        return app(StoreCourseRequestAction::class)->execute(Auth::user(), $request->all());
    }

    // TEACHER: Show a single request
    public function show($id)
    {
        return app(TeacherShowRequestAction::class)->execute(Auth::user(), $id);
    }

    // TEACHER: Edit a rejected request
    public function edit($id)
    {
        return app(EditRejectedRequestAction::class)->execute(Auth::user(), $id);
    }

    // TEACHER: Update and resubmit a rejected request
    public function update(Request $request, $id)
    {
        return app(UpdateRejectedRequestAction::class)->execute(Auth::user(), $id, $request->all());
    }

    // ADMIN: List all requests
    public function adminIndex()
    {
        return app(ListAllRequestsAction::class)->execute(Auth::user());
    }

    // ADMIN: Show a single request
    public function adminShow($id)
    {
        return app(AdminShowRequestAction::class)->execute(Auth::user(), $id);
    }

    // ADMIN: Approve a request (creates a Course)
    public function approve($id)
    {
        return app(ApproveRequestAction::class)->execute(Auth::user(), $id);
    }

    // ADMIN: Reject a request
    public function reject(Request $request, $id)
    {
        return app(RejectRequestAction::class)->execute(Auth::user(), $id, $request->all());
    }
}