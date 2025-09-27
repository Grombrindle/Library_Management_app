<?php

namespace App\Services\Quiz;

use App\Actions\Quiz\FetchScoreAction;
use App\Actions\Quiz\CheckScoresAction;
use App\Actions\Quiz\FinishQuizAction;
use App\Actions\Quiz\EditQuizAction;

class QuizService
{
    protected FetchScoreAction $fetchScoreAction;
    protected CheckScoresAction $checkScoresAction;
    protected FinishQuizAction $finishQuizAction;
    protected EditQuizAction $editQuizAction;

    public function __construct(
        FetchScoreAction $fetchScoreAction,
        CheckScoresAction $checkScoresAction,
        FinishQuizAction $finishQuizAction,
        EditQuizAction $editQuizAction
    ) {
        $this->fetchScoreAction = $fetchScoreAction;
        $this->checkScoresAction = $checkScoresAction;
        $this->finishQuizAction = $finishQuizAction;
        $this->editQuizAction = $editQuizAction;
    }

    public function fetchScore(int $lectureId): array
    {
        return $this->fetchScoreAction->execute($lectureId);
    }

    public function checkScores(int $courseId): array
    {
        return $this->checkScoresAction->execute($courseId);
    }

    public function finish(int $quizId, array $correctAnswers): array
    {
        return $this->finishQuizAction->execute($quizId, $correctAnswers);
    }

    public function edit(int $quizId, array $quizData): void
    {
        $this->editQuizAction->execute($quizId, $quizData);
    }
}