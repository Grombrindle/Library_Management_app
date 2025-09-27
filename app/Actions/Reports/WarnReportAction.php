<?php

namespace App\Actions\Reports;

use App\Models\Report;
use App\Models\LectureRating;
use App\Models\CourseRating;
use App\Models\ResourceRating;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class WarnReportAction
{
    public function execute(int $reportId): ?array
    {
        $report = Report::find($reportId);
        if (!$report) return null;

        $rating = LectureRating::find($report->lecture_rating_id)
            ?? CourseRating::find($report->course_rating_id)
            ?? ResourceRating::find($report->resource_rating_id);

        if (!$rating) return null;

        $rating->isHidden = true;
        $rating->save();

        $user = User::find($rating->user_id);
        $user->increment('counter');
        $user->hasWarning = true;
        $user->comment = $rating->review;
        $user->save();

        $report->status = "WARNED";
        $report->handled_by_id = Auth::id();
        $report->save();

        return ['id' => $report->id, 'status' => $report->status, 'user_id' => $user->id];
    }
}