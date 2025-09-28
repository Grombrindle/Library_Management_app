<?php

namespace App\Actions\Resources;

use App\Models\ResourceRating;
use Illuminate\Support\Facades\Auth;

class RateResourceAction
{
    public function execute(array $data): array
    {
        $rating = ResourceRating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'resource_id' => $data['resource_id'],
            ],
            [
                'rating' => $data['rating'],
                'review' => $data['review'] ?? null,
            ]
        );

        return [
            'success' => true,
            'rating' => $rating,
        ];
    }
}