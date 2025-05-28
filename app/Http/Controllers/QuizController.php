<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\score;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Quiz;

class QuizController extends Controller
{
    
    //

    public function fetchScore($id) {
        $score = score::where('user_id', Auth::user()->id)
                     ->where('quiz_id', Lecture::findOrFail($id)->quiz_id)
                     ->first();
        
        return response()->json([
            'success' => true,
            'score' => $score ? $score->correctAnswers : null,
            'questions' => Lecture::findOrFail($id)->quiz->questions->count(),
        ]);
    }

    public function checkScores($id) {
        $course = Course::findOrFail($id);
        $lectures = $course->lectures;
        $scores = [];
        foreach ($lectures as $lecture) {
            if ($lecture->quiz) {
                $score = score::where('user_id', Auth::user()->id)
                            ->where('quiz_id', $lecture->quiz_id)
                            ->first();
                            
                $scores[] = [
                    'lecture_id' => $lecture->id,
                    'lecture_name' => $lecture->name,
                    'score' => $score ? $score->correctAnswers : null,
                    'questions' => $lecture->quiz->questions->count()
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'scores' => $scores
        ]);
    }

    public function finish(Request $request, $id)
    {
        $score = score::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'quiz_id' => $id
            ],
            [
                'correctAnswers' => $request->input('correctAnswers')
            ]
        );
        return response()->json([
            'success' => true,
            'status' => $score->wasRecentlyCreated ? 'created' : 'updated'
        ]);
    }
}
