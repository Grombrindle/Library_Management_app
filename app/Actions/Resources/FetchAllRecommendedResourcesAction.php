<?php

namespace App\Actions\Resources;

use App\Models\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FetchAllRecommendedResourcesAction
{
    public function execute()
    {
        $user = Auth::user();

        if (!$user) {
            return Resource::withCount(['users', 'ratings'])
                ->withAvg('ratings', 'rating')
                ->orderByDesc(DB::raw('
                    (
                        (COALESCE(ratings_avg_rating, 0) * 0.6) +
                        (ratings_count * 0.3) +
                        (users_count * 0.1)
                    ) *
                    (1 + (COALESCE(ratings_avg_rating, 0) / 5))
                '))
                ->get();
        }

        $userSubjectIds = $user->courses()->pluck('subject_id')->unique();
        $userSubjectTypes = DB::table('subjects')
            ->whereIn('id', $userSubjectIds)
            ->pluck('literaryOrScientific')
            ->unique();

        return Resource::withCount(['ratings'])
            ->withAvg('ratings', 'rating')
            ->with('subject')
            ->orderByDesc(DB::raw('
                (
                    (COALESCE(ratings_avg_rating, 0) * 0.4) +
                    (ratings_count * 0.3)+
                    (
                        CASE
                            WHEN subject_id IN (' . $userSubjectIds->implode(',') . ') THEN 0.3
                            ELSE 0
                        END
                    ) +
                    (
                        CASE
                            WHEN literaryOrScientific IN (' . $userSubjectTypes->implode(',') . ') THEN 0.2
                            ELSE 0
                        END
                    )
                ) *
                (1 + (COALESCE(ratings_avg_rating, 0) / 5))
            '))
            ->get();
    }
}