<?php

namespace App\Actions\Likes;

use App\Models\Lecture;

class FetchLikesAction
{
    public function execute(int $lectureId): array
    {
        $lecture = Lecture::find($lectureId);

        if (!$lecture) {
            return [
                'success' => false,
                'message' => 'Lecture not found'
            ];
        }

        return [
            'success' => true,
            'likes' => $lecture->likes()->where('isLiked', true)->count(),
            'dislikes' => $lecture->likes()->where('isDisliked', true)->count(),
            'isLiked' => $lecture->is_liked,
            'isDisliked' => $lecture->is_disliked,
        ];
    }
}