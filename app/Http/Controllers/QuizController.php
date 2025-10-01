<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuizService;
use Illuminate\Support\Facades\Session;

use App\Actions\Quiz\FinishQuizAction;
use App\Actions\Quiz\EditQuizAction;


class QuizController extends Controller
{

    protected FinishQuizAction $finishQuizAction;
    protected EditQuizAction $editQuizAction;
    protected QuizService $quizService;

    public function __construct(
        QuizService $quizService,
        FinishQuizAction $finishQuizAction,
        EditQuizAction $editQuizAction
    ) {
        $this->finishQuizAction = $finishQuizAction;
        $this->editQuizAction = $editQuizAction;
        $this->quizService = $quizService;
    }

    public function fetchScore($id)
    {
        $data = $this->quizService->fetchScore($id);
        return response()->json($data);
    }

    public function checkScores($id)
    {
        $data = $this->quizService->checkScores($id);
        return response()->json($data);
    }

    public function finish(Request $request, $id)
    {
        $correctAnswers = $request->input('correctAnswers', []);
        $data = $this->finishQuizAction->execute($id, $correctAnswers);
        return response()->json($data);
    }

    public function edit(Request $request, $id)
    {
        $quizData = json_decode($request->input('quiz_data'), true);
        $this->editQuizAction->execute($id, $quizData);

        $quiz = \App\Models\Quiz::findOrFail($id);
        $data = ['element' => 'lecture', 'id' => $quiz->lecture->id, 'name' => $quiz->lecture->name];
        Session::put('update_info', $data);
        Session::put('link', '/lectures');

        return redirect()->route('update.confirmation');
    }
}
