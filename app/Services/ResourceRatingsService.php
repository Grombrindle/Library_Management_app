<?php

namespace App\Services;

use App\Models\ResourceRating;
use Illuminate\Support\Facades\Auth;
use App\Models\Resource;


class ResourceRatingsService
{

    /* Fetch ratings for a resource */
    public function getRatings(int $resourceId)
    {

        $ratings = Resource::find($resourceId)->ratings()
            ->where('isHidden', false)
            ->whereNotNull('review')
            ->get();


        if (!$ratings) {
            return [];
        }

        return response()->json([
            'success' => true,
            'featuredRatings' => $ratings
        ]);
    }

    public function getFeaturedRatings($id)
    {

        $resource = Resource::find($id);

        return response()->json([
            'success' => true,
            'featuredRatings' => $resource->getFeaturedRatingsAttribute(),
        ]);
    }

    /* Create/update rating for a resource */
    public function rate(int $resourceId, int $rating, ?string $review = null)
    {

        $resource = Resource::find($resourceId);

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
            'featuredRatings' => $resource->featured_ratings,
            'rating_breakdown' => $resource->rating_breakdown,
            'resourceRating' => $resource->rating
        ];
    }

}

