<?php

namespace App\Actions\Helpful;

use App\Models\Helpful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseRating;
use App\Models\LectureRating;
use App\Models\ResourceRating;
use App\Models\TeacherRating;

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

        $typeColumn = null;
        $typeId = null;

        if ($request->lecture_rating_id) {
            $typeColumn = 'lecture_rating_id';
            $typeId = $request->lecture_rating_id;
        } elseif ($request->course_rating_id) {
            $typeColumn = 'course_rating_id';
            $typeId = $request->course_rating_id;
        } elseif ($request->teacher_rating_id) {
            $typeColumn = 'teacher_rating_id';
            $typeId = $request->teacher_rating_id;
        } elseif ($request->resource_rating_id) {
            $typeColumn = 'resource_rating_id';
            $typeId = $request->resource_rating_id;
        }

        $helpfulCount = 0;
        $unhelpfulCount = 0;

        if ($typeColumn == 'lecture_rating_id') {
            $helpfulCount = LectureRating::find($typeId)->helpful()->count();
            $unhelpfulCount = LectureRating::find($typeId)->unhelpful()->count();
        }
        if ($typeColumn == 'course_rating_id') {
            $helpfulCount = CourseRating::find($typeId)->helpful()->count();
            $unhelpfulCount = CourseRating::find($typeId)->unhelpful()->count();
        }
        if ($typeColumn == 'resource_rating_id') {
            $helpfulCount = ResourceRating::find($typeId)->helpful()->count();
            $unhelpfulCount = ResourceRating::find($typeId)->unhelpful()->count();
        }
        if ($typeColumn == 'teacher_rating_id') {
            $helpfulCount = TeacherRating::find($typeId)->helpful()->count();
            $unhelpfulCount = TeacherRating::find($typeId)->unhelpful()->count();
        }




        return [
            'success' => true,
            'action' => $action,
            'helpfulCount' => $helpfulCount,
            'unhelpfulCount' => $unhelpfulCount,
        ];
    }
}