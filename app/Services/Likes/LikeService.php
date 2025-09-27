<?php

namespace App\Services\Likes;

use App\Actions\Likes\FetchLikesAction;
use App\Actions\Likes\ToggleLikeAction;
use App\Actions\Likes\ToggleDislikeAction;

class LikeService
{
    public function __construct(
        private FetchLikesAction $fetchLikesAction,
        private ToggleLikeAction $toggleLikeAction,
        private ToggleDislikeAction $toggleDislikeAction,
    ) {}

    public function fetchLikes(int $lectureId): array
    {
        return $this->fetchLikesAction->execute($lectureId);
    }

    public function toggleLike(int $lectureId): array
    {
        return $this->toggleLikeAction->execute($lectureId);
    }

    public function toggleDislike(int $lectureId): array
    {
        return $this->toggleDislikeAction->execute($lectureId);
    }
}