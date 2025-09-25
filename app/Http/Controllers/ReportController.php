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
        // Determine exactly one target rating id
        $targets = [
            'lecture_rating_id' => $request->lecture_rating_id,
            'course_rating_id' => $request->course_rating_id,
            'resource_rating_id' => $request->resource_rating_id,
            'teacher_rating_id' => $request->teacher_rating_id,
        ];
        $active = array_filter($targets, fn($v) => !is_null($v));
        if (count($active) !== 1) {
            return response()->json([
                'success' => false,
                'reason' => 'Exactly one rating id must be provided'
            ], 422);
        }

        $key = array_key_first($active);
        $ratingId = $active[$key];

        // Resolve rating model and owner user
        $rating = null;
        if ($key === 'lecture_rating_id') $rating = LectureRating::find($ratingId);
        if ($key === 'course_rating_id') $rating = CourseRating::find($ratingId);
        if ($key === 'resource_rating_id') $rating = ResourceRating::find($ratingId);
        if ($key === 'teacher_rating_id') $rating = TeacherRating::find($ratingId);

        if (!$rating) {
            return response()->json([
                'success' => false,
                'reason' => 'No such review'
            ], 404);
        }

        // Check existing report by same reporter for the same exact rating
        $existing = Report::where('reporter_id', Auth::id())
            ->where($key, $ratingId)
            ->first();

        if ($existing) {
            $existing->reasons = $request->reasons;
            $existing->message = $request->message;
            if ($key === 'lecture_rating_id') $existing->lecture_comment = $rating->review;
            if ($key === 'course_rating_id') $existing->course_comment = $rating->review;
            if ($key === 'resource_rating_id') $existing->resource_comment = $rating->review;
            if ($key === 'teacher_rating_id') $existing->teacher_comment = $rating->review;
            $existing->save();

            return response()->json(['success' => true, 'status' => "updated"]);
        }

        // Create new report
        $data = [
            'user_id' => $rating->user_id,
            'reporter_id' => Auth::id(),
            'reasons' => $request->reasons,
            'message' => $request->message,
            'lecture_comment' => null,
            'course_comment' => null,
            'resource_comment' => null,
            'teacher_comment' => null,
            'lecture_rating_id' => null,
            'course_rating_id' => null,
            'resource_rating_id' => null,
            'teacher_rating_id' => null,
        ];
        $data[$key] = $ratingId;
        if ($key === 'lecture_rating_id') $data['lecture_comment'] = $rating->review;
        if ($key === 'course_rating_id') $data['course_comment'] = $rating->review;
        if ($key === 'resource_rating_id') $data['resource_comment'] = $rating->review;
        if ($key === 'teacher_rating_id') $data['teacher_comment'] = $rating->review;

        Report::create($data);

        return response()->json(['success' => true, 'status' => "created"]);
    }

    public function ignore($id, \App\Actions\Reports\IgnoreReportAction $ignoreReport)
    {
        $report = Report::find($id);
        if ($report) {
            $ignoreReport($report);
            $user = User::find($report->user_id);
            $data = ['id' => $id, 'name' => $user?->userName, 'message' => 'ignored'];
            session(['user_info' => $data]);
            session(['link' => '/reports']);
            return redirect()->route('user.confirmation');
        }
        return response()->json(['success' => false]);
    }

    public function warn($id, \App\Actions\Reports\WarnReportAction $warnReport)
    {
        $report = Report::find($id);
        if ($report) {
            $user = $warnReport($report);
            $data = ['id' => $id, 'name' => $user?->userName, 'message' => 'warned'];
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
