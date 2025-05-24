<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\score;
use Carbon\Carbon;

class QuizController extends Controller
{
    //
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
