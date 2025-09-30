<?php

namespace App\Services\CourseRequests\Admin;

use App\Models\Admin;
use App\Models\Teacher;
use App\Models\CourseRequest;

class CourseRequestShowService
{
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

}
