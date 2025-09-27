<?php

namespace App\Actions\Quiz;

use App\Models\Quiz;
use App\Models\Question;

class EditQuizAction
{
    public function execute(int $quizId, array $quizData): void
    {
        $quiz = Quiz::findOrFail($quizId);

        // Remove old questions
        $quiz->questions()->delete();

        // Add new questions
        foreach ($quizData as $question) {
            $originalOptions = $question['options'];
            $options = array_values(array_filter($originalOptions, fn($opt) => isset($opt) && trim($opt) !== ''));

            $originalIndex = $question['correctAnswerIndex'];
            $nonEmptyIndexes = array_keys(array_filter($originalOptions, fn($opt) => isset($opt) && trim($opt) !== ''));
            $newCorrectIndex = array_search($originalIndex, $nonEmptyIndexes);
            if ($newCorrectIndex === false) $newCorrectIndex = 0;

            Question::create([
                'questionText' => $question['questionText'],
                'options' => json_encode($options),
                'correctAnswerIndex' => $newCorrectIndex,
                'quiz_id' => $quiz->id,
            ]);
        }
    }
}