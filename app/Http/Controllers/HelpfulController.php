<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Helpful;

class HelpfulController extends Controller
{
    public function toggleHelpful(Request $request)
    {
        $request->validate([
            'lecture_rating_id' => 'nullable|exists:lecture_rating,id',
            'course_rating_id' => 'nullable|exists:course_rating,id',
            'teacher_rating_id' => 'nullable|exists:teacher_ratings,id',
            'resource_rating_id' => 'nullable|exists:resources_ratings,id',
        ]);

        // Ensure exactly one rating ID is provided
        $ratingCount = 0;
        $ratingCount += $request->lecture_rating_id ? 1 : 0;
        $ratingCount += $request->course_rating_id ? 1 : 0;
        $ratingCount += $request->teacher_rating_id ? 1 : 0;
        $ratingCount += $request->resource_rating_id ? 1 : 0;

        if ($ratingCount !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Exactly one rating ID must be provided'
            ], 422);
        }

        $user = Auth::user();

        // Check if helpful record already exists
        $helpful = Helpful::where('user_id', $user->id)
            ->where('lecture_rating_id', $request->lecture_rating_id)
            ->where('course_rating_id', $request->course_rating_id)
            ->where('teacher_rating_id', $request->teacher_rating_id)
            ->where('resource_rating_id', $request->resource_rating_id)
            ->first();

        if ($helpful) {
            if ($helpful->isHelpful) {
                // Already helpful, remove it
                $helpful->delete();
                $action = 'removed';
            } else {
                // Currently unhelpful, change to helpful
                $helpful->isHelpful = true;
                $helpful->save();
                $action = 'marked_helpful';
            }
        } else {
            // Create new helpful record
            Helpful::create([
                'user_id' => $user->id,
                'lecture_rating_id' => $request->lecture_rating_id,
                'course_rating_id' => $request->course_rating_id,
                'teacher_rating_id' => $request->teacher_rating_id,
                'resource_rating_id' => $request->resource_rating_id,
                'isHelpful' => true
            ]);
            $action = 'marked_helpful';
        }

        return response()->json([
            'success' => true,
            'action' => $action
        ]);
    }

    public function toggleUnhelpful(Request $request)
    {
        $request->validate([
            'lecture_rating_id' => 'nullable|exists:lecture_rating,id',
            'course_rating_id' => 'nullable|exists:course_rating,id',
            'teacher_rating_id' => 'nullable|exists:teacher_ratings,id',
            'resource_rating_id' => 'nullable|exists:resources_ratings,id',
        ]);

        // Ensure exactly one rating ID is provided
        $ratingCount = 0;
        $ratingCount += $request->lecture_rating_id ? 1 : 0;
        $ratingCount += $request->course_rating_id ? 1 : 0;
        $ratingCount += $request->teacher_rating_id ? 1 : 0;
        $ratingCount += $request->resource_rating_id ? 1 : 0;
        if ($ratingCount !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Exactly one rating ID must be provided'
            ], 422);
        }

        $user = Auth::user();

        // Check if helpful record already exists
        $helpful = Helpful::where('user_id', $user->id)
            ->where(function ($query) use ($request) {
                if ($request->lecture_rating_id) {
                    $query->where('lecture_rating_id', $request->lecture_rating_id);
                }
                if ($request->course_rating_id) {
                    $query->where('course_rating_id', $request->course_rating_id);
                }
                if ($request->teacher_rating_id) {
                    $query->where('teacher_rating_id', $request->teacher_rating_id);
                }
                if ($request->resource_rating_id) {
                    $query->where('resource_rating_id', $request->resource_rating_id);
                }
            })
            ->first();

        if ($helpful) {
            if (!$helpful->isHelpful) {
                // Already unhelpful, remove it
                $helpful->delete();
                $action = 'removed';
            } else {
                // Currently helpful, change to unhelpful
                $helpful->isHelpful = false;
                $helpful->save();
                $action = 'marked_unhelpful';
            }
        } else {
            // Create new unhelpful record
            Helpful::create([
                'user_id' => $user->id,
                'lecture_rating_id' => $request->lecture_rating_id,
                'course_rating_id' => $request->course_rating_id,
                'teacher_rating_id' => $request->teacher_rating_id,
                'resource_rating_id' => $request->resource_rating_id,
                'isHelpful' => false
            ]);
            $action = 'marked_unhelpful';
        }

        return response()->json([
            'success' => true,
            'action' => $action
        ]);
    }
}
