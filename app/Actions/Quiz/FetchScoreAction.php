<?php

namespace App\Actions\Quiz;

use App\Models\Score;
use App\Models\Lecture;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class FetchScoreAction
{
    public function execute(int $lectureId): array
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
}