<?php

namespace App\Services;

use App\Models\ResourceRating;
use Illuminate\Support\Facades\Auth;
use App\Models\Resource;


class ResourceRatingsService
{

    /* Fetch ratings for a resource */
    public function getRatings(int $resourceId): array
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

    public function getFeaturedRatings($id)
    {

        $resource = Resource::find($id);

        return response()->json([
            'success' => true,
            'FeaturedRatings' => $resource->getFeaturedRatingsAttribute(),
        ]);
    }

    /* Create/update rating for a resource */
    public function rate(int $resourceId, int $rating, ?string $review = null): array
    {

        $rating = ResourceRating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'resource_id' => $resourceId,
            ],
            [
                'rating' => $rating,
                'review' => $review ?? null,
            ]
        );

        return [
            'success' => true,
            'rating' => $rating->rating,
            'review' => $rating->review,
        ];
    }

}

