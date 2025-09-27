<?php

namespace App\Actions\Helpful;

use App\Models\Helpful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToggleHelpfulAction
{
    public function execute(Request $request): array
    {
        $user = Auth::user();

        $helpful = Helpful::where('user_id', $user->id)
            ->where('lecture_rating_id', $request->lecture_rating_id)
            ->where('course_rating_id', $request->course_rating_id)
            ->where('teacher_rating_id', $request->teacher_rating_id)
            ->where('resource_rating_id', $request->resource_rating_id)
            ->first();

        if ($helpful) {
            if ($helpful->isHelpful) {
                $helpful->delete();
                $action = 'removed';
            } else {
                $helpful->isHelpful = true;
                $helpful->save();
                $action = 'marked_helpful';
            }
        } else {
            Helpful::create([
                'user_id' => $user->id,
                'lecture_rating_id' => $request->lecture_rating_id,
                'course_rating_id' => $request->course_rating_id,
                'teacher_rating_id' => $request->teacher_rating_id,
                'resource_rating_id' => $request->resource_rating_id,
                'isHelpful' => true,
            ]);
            $action = 'marked_helpful';
        }

        return ['success' => true, 'action' => $action];
    }
}