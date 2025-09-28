<?php

namespace App\Actions\Resources;

use App\Models\Resource;

class FetchAllRatedResourcesAction
{
    public function execute()
    {
        return Resource::withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->get();
    }
}