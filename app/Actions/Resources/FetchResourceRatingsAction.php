<?php

namespace App\Actions\Resources;

use App\Models\ResourceRating;

class FetchResourceRatingsAction
{
    public function execute(int $resourceId): array
    {
        $ratings = ResourceRating::with('user')
            ->where('resource_id', $resourceId)
            ->latest()
            ->get();

        return [
            'success' => true,
            'ratings' => $ratings,
        ];
    }
}