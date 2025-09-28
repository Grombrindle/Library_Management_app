<?php

namespace App\Actions\Lectures;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class GetCourseLecturesRatedAction
{
    public function execute(int $courseId): array
    {
        $course = Course::find($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found'
            ];
        }

        $lectures = $course->lectures()
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->get();

        $lectures->each(function ($lecture) {
            if ($lecture->quiz) {
                $score = \App\Models\Score::where('user_id', Auth::id())
                    ->where('quiz_id', $lecture->quiz->id)
                    ->first();
                $lecture->score = $score ? $score->correctAnswers : null;
                $lecture->number_of_questions = $lecture->quiz->questions->count();
            } else {
                $lecture->score = null;
                $lecture->number_of_questions = 0;
            }

            $lecture->url360 = $lecture->file_360 ? url($lecture->file_360) : null;
            $lecture->url720 = $lecture->file_720 ? url($lecture->file_720) : null;
            $lecture->url1080 = $lecture->file_1080 ? url($lecture->file_1080) : null;
            $lecture->urlpdf = $lecture->file_pdf ? url($lecture->file_pdf) : null;
        });

        return [
            'success' => true,
            'lectures' => $lectures,
            'course' => [
                'id' => $course->id,
                'name' => $course->name,
                'subject_id' => $course->subject_id
            ]
        ];
    }
}