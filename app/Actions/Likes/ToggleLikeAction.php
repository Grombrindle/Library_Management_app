<?php

namespace App\Actions\Likes;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use App\Models\Lecture;

class ToggleLikeAction
{
    public function execute(int $lectureId): array
    {
        $user = Auth::user();

        $like = Like::where('user_id', $user->id)
            ->where('lecture_id', $lectureId)
            ->first();

        if ($like) {
            if ($like->isLiked) {
                $like->delete();
                $action = 'removed';
            } else {
                $like->isLiked = true;
                $like->isDisliked = false;
                $like->save();
                $action = 'marked_liked';
            }
        } else {
            Like::create([
                'user_id' => $user->id,
                'lecture_id' => $lectureId,
                'isLiked' => true,
                'isDisliked' => false,
            ]);
            $action = 'marked_liked';
        }

        return [
            'success' => true,
            'action' => $action,
            'likes' => Lecture::find($lectureId)->likes,
            'dislikes' => Lecture::find($lectureId)->dislikes,
            'isLiked' => $action === 'marked_liked',
            'isDisliked' => false,
        ];
    }
}