<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\score;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Quiz;
use App\Models\question;
use App\Models\User;

class QuizController extends Controller
{

    //

    public function fetchScore($id)
    {
        $score = score::where('user_id', Auth::user()->id)
            ->where('quiz_id', Lecture::findOrFail($id)->quiz_id)
            ->first();

        return response()->json([
            'success' => true,
            'score' => $score ? $score->correctAnswers : null,
            'questions' => Lecture::findOrFail($id)->quiz->questions->count(),
        ]);
    }

    public function checkScores($id)
    {
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
                'correctAnswers' => json_encode($request->input('correctAnswers')),
                // We'll set sparks and sparkies below after calculation
            ]
        );

        $sparky = null;
        $sparks = 0;
        $total_sparkies = null;
        // Only execute if the score was just created
        if ($score->wasRecentlyCreated) {
            // Calculate sparks
            $correctAnswers = $request->input('correctAnswers', []);
            $quiz = Quiz::findOrFail($id);
            $questions = $quiz->questions()->orderBy('id')->get();
            foreach ($questions as $index => $question) {
                if (isset($correctAnswers[$index]) && $correctAnswers[$index]) {
                    switch ($question->difficulty) {
                        case 'EASY':
                            $sparks += 1;
                            break;
                        case 'MEDIUM':
                            $sparks += 3;
                            break;
                        case 'HARD':
                            $sparks += 5;
                            break;
                    }
                }
            }
            $user = Auth::user();
            $user->sparks += $sparks;
            if ($user->sparks >= 1000) {
                $user->sparks -= 1000;
                $user->sparkies += 1;
                $sparky = true;
            }
            $user->save();
            $total_sparkies = $user->sparkies;
            // Store the user's total sparks and sparkies at quiz completion
            $score->sparks = $user->sparks;
            $score->sparkies = $user->sparkies;
            $score->save();
        } else {
            $user = Auth::user();
            $total_sparkies = $user->sparkies;
        }

        return response()->json([
            'success' => true,
            'status' => $score->wasRecentlyCreated ? 'created' : 'updated',
            'sparks_added' => $sparks,
            'total_sparks' => $user->sparks,
            'sparky_added' => $sparky ? 'true' : false,
            'total_sparkies' => $total_sparkies
        ]);
    }
    public function edit(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        $data = json_decode($request->input('quiz_data'), true);
        // Remove old questions
        $quiz->questions()->delete();
        // Add new questions
        foreach ($data as $question) {
            // Remove empty options
            $originalOptions = $question['options'];
            $options = array_values(array_filter($originalOptions, function ($opt) {
                return isset($opt) && trim($opt) !== '';
            }));
            // Map the original correctAnswerIndex to the new filtered array
            $originalIndex = $question['correctAnswerIndex'];
            $nonEmptyIndexes = array_keys(array_filter($originalOptions, function ($opt) {
                return isset($opt) && trim($opt) !== '';
            }));
            $newCorrectIndex = array_search($originalIndex, $nonEmptyIndexes);
            if ($newCorrectIndex === false) $newCorrectIndex = 0;
            question::create([
                'questionText' => $question['questionText'],
                'options' => json_encode($options),
                'correctAnswerIndex' => $newCorrectIndex,
                'quiz_id' => $quiz->id,
            ]);
        }

        $data = ['element' => 'lecture', 'id' => $quiz->lecture->id, 'name' => $quiz->lecture->name];
        session(['update_info' => $data]);
        session(['link' => '/lectures']);

        return redirect()->route('update.confirmation');
    }
}
