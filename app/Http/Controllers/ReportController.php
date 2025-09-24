<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseRating;
use App\Models\LectureRating;
use App\Models\ResourceRating;
use App\Models\TeacherRating;
use App\Models\User;

class ReportController extends Controller
{
    //
    public function add(Request $request)
    {

        if (!(LectureRating::find($request->lecture_rating_id) || CourseRating::find($request->course_rating_id) || ResourceRating::find($request->resource_rating_id) || TeacherRating::find($request->teacher_rating_id))) {
            return response()->json([
                'success' => false,
                'reason' => "No such review"
            ]);
        }

        $report = Report::where('reporter_id', Auth::id())->where('user_id', LectureRating::find($request->lecture_rating_id)->user_id ?? ResourceRating::find($request->resource_rating_id)->user_id ?? CourseRating::find($request->course_rating_id)->user_id ?? TeacherRating::find($request->teacher_rating_id)->user_id)->first();



        if ($report && (($report->lecture_rating_id == $request->lecture_rating_id) || ($report->teacher_rating_id == $request->teacher_rating_id) || ($report->course_rating_id == $request->course_rating_id) || ($report->resource_rating_id == $request->resource_rating_id))) {
            return response()->json([
                'success' => false,
                'reason' => "Can't report the same comment more than once"
            ]);
        }

        $report = Report::make([
            'user_id' => LectureRating::find($request->lecture_rating_id)->user_id ?? ResourceRating::find($request->resource_rating_id)->user_id ?? CourseRating::find($request->course_rating_id?? TeacherRating::find($request->teacher_rating_id)->user_id)->user_id,
            'reporter_id' => Auth::id(),
            'lecture_comment' => $request->lecture_rating_id ? LectureRating::find($request->lecture_rating_id)->review : null,
            'lecture_rating_id' => $request->lecture_rating_id ?? null,
            'teacher_comment' => $request->teacher_rating_id ? TeacherRating::find($request->teacher_rating_id)->review : null,
            'teacher_rating_id' => $request->teacher_rating_id ?? null,
            'course_comment' => $request->course_rating_id ? CourseRating::find($request->course_rating_id)->review : null,
            'course_rating_id' => $request->course_rating_id ?? null,
            'resource_comment' => $request->resource_rating_id ? ResourceRating::find($request->resource_rating_id)->review : null,
            'resource_rating_id' => $request->resource_rating_id ?? null,
            'reasons' => $request->reasons,
            'message' => $request->message,
        ]);

        $report->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function ignore($id)
    {
        $report = Report::find($id);

        $user = null;

        if ($report) {
            $report->status = "IGNORED";
            $report->handled_by_id = Auth::id();
            $report->save();

            $user = User::find($report->user_id);

            $data = ['id' => $id, 'name' => $user->userName, 'message' => 'ignored'];
            session(['user_info' => $data]);
            session(['link' => '/reports']);
            return redirect()->route('user.confirmation');
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function warn($id)
    {
        $report = Report::find($id);
        if ($report) {
            $rating = LectureRating::find($report->lecture_rating_id) ?? CourseRating::find($report->course_rating_id) ?? ResourceRating::find($report->resource_rating_id);
            $user = User::find($rating->user_id);
            $user->increment('counter');
            $user->hasWarning = true;
            $user->comment = $rating->review;
            $user->save();

            $report->status = "WARNED";
            $report->handled_by_id = Auth::id();
            $report->save();



            $data = ['id' => $id, 'name' => $user->userName, 'message' => 'warned'];
            session(['user_info' => $data]);
            session(['link' => '/reports']);
            return redirect()->route('user.confirmation');
        }
    }

    public function test(): void
    {
        $report = Report::first();

        switch ($report->status) {
            case 'PENDING':
                $report->status = "IGNORED";
                break;
            case 'IGNORED':
                $report->status = "WARNED";
                break;
            case 'WARNED':
                $report->status = "BANNED";
                break;
            case 'BANNED':
                $report->status = "PENDING";
                break;
        }
        $report->save();
    }
}
