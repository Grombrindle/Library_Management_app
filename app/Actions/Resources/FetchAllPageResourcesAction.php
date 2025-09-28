<?php

namespace App\Actions\Resources;

use App\Models\Resource;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FetchAllPageResourcesAction
{
    public function execute()
    {
        $cacheKey = 'page_resources_' . (Auth::id() ?? 'guest');
        $cacheDuration = 300;

        return Cache::remember($cacheKey, $cacheDuration, function () {
            $userSubjectIds = Auth::user()?->courses()->pluck('subject_id')->unique() ?? collect();
            $userSubjectTypes = $userSubjectIds->isNotEmpty()
                ? DB::table('subjects')->whereIn('id', $userSubjectIds)->pluck('literaryOrScientific')->unique()
                : collect();

            $recommendedResources = Resource::withCount(['ratings'])
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

            $topRatedResources = Resource::withAvg('ratings', 'rating')
                ->orderByDesc('ratings_avg_rating')
                ->get();

            $recentResources = Resource::orderByDesc('created_at')->get();

            return [
                'scientificSubjects' => Subject::where('literaryOrScientific', 1)
                    ->select(['id', 'name', 'literaryOrScientific', 'image'])
                    ->get()
                    ->map(fn($subject) => [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'literaryOrScientific' => $subject->literaryOrScientific,
                        'image' => $subject->image,
                        'imageUrl' => url($subject->image),
                    ]),
                'literarySubjects' => Subject::where('literaryOrScientific', 0)
                    ->select(['id', 'name', 'literaryOrScientific', 'image'])
                    ->get()
                    ->map(fn($subject) => [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'literaryOrScientific' => $subject->literaryOrScientific,
                        'image' => $subject->image,
                        'imageUrl' => url($subject->image),
                    ]),
                'recommended' => $recommendedResources,
                'top_rated' => $topRatedResources,
                'recent' => $recentResources,
            ];
        });
    }
}