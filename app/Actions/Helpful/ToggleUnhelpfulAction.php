<?php

namespace App\Actions\Helpful;

use App\Models\Helpful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToggleUnhelpfulAction
{
    public function execute(Request $request): array
    {
        $user = Auth::user();

        $helpful = Helpful::where('user_id', $user->id)
            ->where(function ($query) use ($request) {
                if ($request->lecture_rating_id) $query->where('lecture_rating_id', $request->lecture_rating_id);
                if ($request->course_rating_id) $query->where('course_rating_id', $request->course_rating_id);
                if ($request->teacher_rating_id) $query->where('teacher_rating_id', $request->teacher_rating_id);
                if ($request->resource_rating_id) $query->where('resource_rating_id', $request->resource_rating_id);
            })
            ->first();

        if ($helpful) {
            if (!$helpful->isHelpful) {
                $helpful->delete();
                $action = 'removed';
            } else {
                $helpful->isHelpful = false;
                $helpful->save();
                $action = 'marked_unhelpful';
            }
        } else {
            Helpful::create([
                'user_id' => $user->id,
                'lecture_rating_id' => $request->lecture_rating_id,
                'course_rating_id' => $request->course_rating_id,
                'teacher_rating_id' => $request->teacher_rating_id,
                'resource_rating_id' => $request->resource_rating_id,
                'isHelpful' => false,
            ]);
            $action = 'marked_unhelpful';
        }

        return ['success' => true, 'action' => $action];
    }
}