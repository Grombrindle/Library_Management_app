<?php

namespace App\Actions\Likes;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;

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
            'action' => $action
        ];
    }
}