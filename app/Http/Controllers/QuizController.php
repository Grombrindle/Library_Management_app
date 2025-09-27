<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Quiz\QuizService;
use Illuminate\Support\Facades\Session;

class QuizController extends Controller
{
    protected QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
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
        $data = $this->quizService->finish($id, $correctAnswers);
        return response()->json($data);
    }

    public function edit(Request $request, $id)
    {
        $quizData = json_decode($request->input('quiz_data'), true);
        $this->quizService->edit($id, $quizData);

        $quiz = \App\Models\Quiz::findOrFail($id);
        $data = ['element' => 'lecture', 'id' => $quiz->lecture->id, 'name' => $quiz->lecture->name];
        Session::put('update_info', $data);
        Session::put('link', '/lectures');

        return redirect()->route('update.confirmation');
    }
}