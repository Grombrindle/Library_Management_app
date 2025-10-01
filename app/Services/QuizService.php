<?php

namespace App\Services;

use App\Models\Score;
use App\Models\Lecture;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;


class QuizService
{

    public function fetchScore(int $lectureId)
    {
        $lecture = Lecture::findOrFail($lectureId);

        $score = Score::where('user_id', Auth::id())
            ->where('quiz_id', $lecture->quiz_id)
            ->first();

        $correctAnswers = 0;

        if ($score) {
            foreach (json_decode($score->correctAnswers) as $answer) {
                $answer == 1 ? $correctAnswers++ : null;
            }
        }

        return [
            'success' => true,
            'score' => $score ? $correctAnswers : null,
            'questions' => $lecture->quiz->questions->count(),
        ];
    }

    public function checkScores(int $courseId)
    {
        $course = Course::findOrFail($courseId);
        $scores = [];

        foreach ($course->lectures as $lecture) {
            if ($lecture->quiz) {
                $score = Score::where('user_id', Auth::id())
                    ->where('quiz_id', $lecture->quiz_id)
                    ->first();

                $correctAnswers = 0;

                if ($score) {
                    foreach (json_decode($score->correctAnswers) as $answer) {
                        $answer == 1 ? $correctAnswers++ : null;
                    }
                }

                $scores[] = [
                    'lecture_id' => $lecture->id,
                    'lecture_name' => $lecture->name,
                    'score' => $score ? $correctAnswers : null,
                    'questions' => $lecture->quiz->questions->count()
                ];
            }
        }

        return [
            'success' => true,
            'scores' => $scores
        ];
    }
}
