<?php

namespace App\Actions\Likes;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use App\Models\Lecture;

class ToggleDislikeAction
{
    public function execute(int $lectureId): array
    {
        $user = Auth::user();

        $like = Like::where('user_id', $user->id)
            ->where('lecture_id', $lectureId)
            ->first();

        if ($like) {
            if ($like->isDisliked) {
                $like->delete();
                $action = 'removed';
            } else {
                $like->isDisliked = true;
                $like->isLiked = false;
                $like->save();
                $action = 'marked_disliked';
            }
        } else {
            Like::create([
                'user_id' => $user->id,
                'lecture_id' => $lectureId,
                'isLiked' => false,
                'isDisliked' => true,
            ]);
            $action = 'marked_disliked';
        }

        return [
            'success' => true,
            'action' => $action,
            'likes' => Lecture::find($lectureId)->likes,
            'dislikes' => Lecture::find($lectureId)->dislikes,
            'isLiked' => false,
            'isDisliked' => $action === 'marked_disliked',
        ];
    }
}