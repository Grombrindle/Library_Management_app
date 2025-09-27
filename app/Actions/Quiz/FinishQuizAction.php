<?php

namespace App\Actions\Quiz;

use App\Models\Score;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class FinishQuizAction
{
    public function execute(int $quizId, array $correctAnswers): array
    {
        $score = Score::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'quiz_id' => $quizId,
            ],
            [
                'correctAnswers' => json_encode($correctAnswers),
                'sparks' => 0
            ]
        );

        $sparks = 0;
        $sparky = null;
        $user = Auth::user();

        if ($score->wasRecentlyCreated) {
            $quiz = Quiz::findOrFail($quizId);
            $questions = $quiz->questions()->orderBy('id')->get();

            foreach ($questions as $index => $question) {
                if (isset($correctAnswers[$index]) && $correctAnswers[$index]) {
                    switch ($question->difficulty) {
                        case 'EASY': $sparks += 1; break;
                        case 'MEDIUM': $sparks += 3; break;
                        case 'HARD': $sparks += 5; break;
                    }
                }
            }

            $user->sparks += $sparks;
            $isSparky = $user->sparks >= 1000;

            if ($isSparky) {
                $user->sparks -= 1000;
                $user->sparkies += 1;
                $sparky = true;
            }

            $user->save();

            $score->sparks = $sparks;
            $score->sparkies = $isSparky ? 1 : 0;
            $score->save();
        } else {
            $sparks = $score->sparks;
        }

        return [
            'success' => true,
            'status' => $score->wasRecentlyCreated ? 'created' : 'updated',
            'sparks_added' => $sparks,
            'total_sparks' => $user->sparks,
            'sparky_added' => $sparky ? 'true' : false,
            'total_sparkies' => $user->sparkies
        ];
    }
}