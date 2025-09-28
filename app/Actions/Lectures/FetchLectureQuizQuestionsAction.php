<?php

namespace App\Actions\Lectures;

use App\Models\Lecture;

class FetchLectureQuizQuestionsAction
{
    public function execute(int $id): array
    {
        $lecture = Lecture::with('quiz.questions')->find($id);

        if (!$lecture) {
            return [
                'success' => false,
                'reason' => 'Lecture not Found'
            ];
        }

        if (!$lecture->quiz) {
            return [
                'success' => false,
                'reason' => 'No Quiz For This Lesson'
            ];
        }

        return [
            'success' => true,
            'quiz' => $lecture->quiz
        ];
    }
}