<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;
use App\Models\Lecture;

class LikeController extends Controller
{
    //
    public function fetchLikes($id)
    {
        $lecture = Lecture::find($id);

        if ($lecture) {
            return response()->json([
                'success' => true,
                'likes' => $lecture->likes()->where('isLiked', true)->count(),
                'dislikes' => $lecture->likes()->where('isDisliked', true)->count(),
            ]);
        }
    }
    public function toggleLike(Request $request)
    {

        $request->validate([
            'lecture_id' => 'nullable|exists:lectures,id',
        ]);

        $user = Auth::user();

        $like = Like::where('user_id', $user->id)
            ->where('lecture_id', $request->lecture_id)
            ->first();

        if ($like) {
            if ($like->isLiked) {
                // Already like, remove it
                $like->delete();
                $action = 'removed';
            } else {
                // Currently unliked, change to like
                $like->isLiked = true;
                $like->isDisliked = false;
                $like->save();
                $action = 'marked_liked';
            }
        } else {
            // Create new like record
            Like::create([
                'user_id' => $user->id,
                'lecture_id' => $request->lecture_id,
                'isLiked' => true,
                'isDisliked' => false,
            ]);
            $action = 'marked_liked';
        }

        return response()->json([
            'success' => true,
            'action' => $action
        ]);
    }
    public function toggleDislike(Request $request)
    {

        $request->validate([
            'lecture_id' => 'nullable|exists:lectures,id',
        ]);

        $user = Auth::user();

        $like = Like::where('user_id', $user->id)
            ->where('lecture_id', $request->lecture_id)
            ->first();

        if ($like) {
            if ($like->isDisliked) {
                // Already like, remove it
                $like->delete();
                $action = 'removed';
            } else {
                // Currently like, change to like
                $like->isDisliked = true;
                $like->isLiked = false;
                $like->save();
                $action = 'marked_disliked';
            }
        } else {
            // Create new like record
            Like::create([
                'user_id' => $user->id,
                'lecture_id' => $request->lecture_id,
                'isLiked' => false,
                'isDisliked' => true,
            ]);
            $action = 'marked_disliked';
        }

        return response()->json([
            'success' => true,
            'action' => $action
        ]);
    }
}
