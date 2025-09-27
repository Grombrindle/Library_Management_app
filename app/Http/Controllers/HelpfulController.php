<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\Helpful\ToggleHelpfulAction;
use App\Actions\Helpful\ToggleUnhelpfulAction;

class HelpfulController extends Controller
{
    protected $toggleHelpfulAction;
    protected $toggleUnhelpfulAction;

    public function __construct(
        ToggleHelpfulAction $toggleHelpfulAction,
        ToggleUnhelpfulAction $toggleUnhelpfulAction
    ) {
        $this->toggleHelpfulAction = $toggleHelpfulAction;
        $this->toggleUnhelpfulAction = $toggleUnhelpfulAction;
    }

    public function toggleHelpful(Request $request)
    {
        $request->validate([
            'lecture_rating_id' => 'nullable|exists:lecture_rating,id',
            'course_rating_id' => 'nullable|exists:course_rating,id',
            'teacher_rating_id' => 'nullable|exists:teacher_ratings,id',
            'resource_rating_id' => 'nullable|exists:resources_ratings,id',
        ]);

        return response()->json($this->toggleHelpfulAction->execute($request));
    }

    public function toggleUnhelpful(Request $request)
    {
        $request->validate([
            'lecture_rating_id' => 'nullable|exists:lecture_rating,id',
            'course_rating_id' => 'nullable|exists:course_rating,id',
            'teacher_rating_id' => 'nullable|exists:teacher_ratings,id',
            'resource_rating_id' => 'nullable|exists:resources_ratings,id',
        ]);

        return response()->json($this->toggleUnhelpfulAction->execute($request));
    }
}