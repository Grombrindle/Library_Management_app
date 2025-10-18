<?php

namespace App\Actions\Reports;

use App\Models\Report;
use App\Models\LectureRating;
use App\Models\CourseRating;
use App\Models\ResourceRating;
use App\Models\TeacherRating;
use Illuminate\Support\Facades\Auth;

class AddReportAction
{
    public function execute(array $data): array
    {
        // Fetch ratings only if IDs are provided
        $lectureRating = isset($data['lecture_rating_id']) && $data['lecture_rating_id']
            ? LectureRating::find($data['lecture_rating_id'])
            : null;

        $courseRating = isset($data['course_rating_id']) && $data['course_rating_id']
            ? CourseRating::find($data['course_rating_id'])
            : null;

        $resourceRating = isset($data['resource_rating_id']) && $data['resource_rating_id']
            ? ResourceRating::find($data['resource_rating_id'])
            : null;

        $teacherRating = isset($data['teacher_rating_id']) && $data['teacher_rating_id']
            ? TeacherRating::find($data['teacher_rating_id'])
            : null;

        // Determine the user_id being reported
        $userId = $lectureRating->user_id
            ?? $courseRating->user_id
            ?? $resourceRating->user_id
            ?? $teacherRating->user_id;

        if (!$userId) {
            return ['success' => false, 'reason' => "No such review"];
        }

        // Check if a report already exists
        $existing = Report::where('reporter_id', Auth::id())
            ->where('user_id', $userId)
            ->where(function ($query) use ($data) {
                if (!empty($data['lecture_rating_id']))
                    $query->orWhere('lecture_rating_id', $data['lecture_rating_id']);
                if (!empty($data['teacher_rating_id']))
                    $query->orWhere('teacher_rating_id', $data['teacher_rating_id']);
                if (!empty($data['course_rating_id']))
                    $query->orWhere('course_rating_id', $data['course_rating_id']);
                if (!empty($data['resource_rating_id']))
                    $query->orWhere('resource_rating_id', $data['resource_rating_id']);
            })
            ->first();

        if ($existing) {
            $existing->message = $data['message'] ?? null;
            $existing->reasons = $data['reasons'] ?? null;

            $existing->save();


            return ['success' => true, 'report' => $existing];
        }
        // Create the report
        $report = Report::create([
            'user_id' => $userId,
            'reporter_id' => Auth::id(),
            'lecture_comment' => $lectureRating->review ?? null,
            'lecture_rating_id' => $data['lecture_rating_id'] ?? null,
            'teacher_comment' => $teacherRating->review ?? null,
            'teacher_rating_id' => $data['teacher_rating_id'] ?? null,
            'course_comment' => $courseRating->review ?? null,
            'course_rating_id' => $data['course_rating_id'] ?? null,
            'resource_comment' => $resourceRating->review ?? null,
            'resource_rating_id' => $data['resource_rating_id'] ?? null,
            'reasons' => $data['reasons'] ?? null,
            'message' => $data['message'] ?? null,
        ]);

        return ['success' => true, 'report' => $report];
    }
}