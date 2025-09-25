<?php

namespace App\Actions\Reports;

use App\Models\Report;
use App\Models\LectureRating;
use App\Models\CourseRating;
use App\Models\ResourceRating;
use App\Models\TeacherRating;
use App\Models\User;
use App\Services\Reports\ReportModerationService;

class WarnReportAction
{
    public function __construct(private ReportModerationService $service) {}

    public function __invoke(Report $report): User
    {
        $rating = LectureRating::find($report->lecture_rating_id)
            ?? CourseRating::find($report->course_rating_id)
            ?? ResourceRating::find($report->resource_rating_id)
            ?? TeacherRating::find($report->teacher_rating_id);

        if ($rating) {
            $rating->isHidden = true;
            $rating->save();

            $user = User::find($rating->user_id);
            if ($user) {
                $user->increment('counter');
                $user->hasWarning = true;
                $user->comment = $rating->review;
                $user->save();
            }
        }

        $this->service->markWarned($report);

        return $user ?? new User();
    }
}


