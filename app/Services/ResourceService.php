<?php

namespace App\Services;

use App\Models\Resource;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use App\Actions\Resources\FetchAllPageResourcesAction;
use App\Actions\Resources\AddResourceAction;
use App\Actions\Resources\EditResourceAction;
use App\Actions\Resources\DeleteResourceAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ResourceService
{
    /* Fetch single resource (nullable id handled in action) */
    public function getResource(?int $id)
    {
        if (!$id) {
            return null;
        }

        return response()->json([

            'success' => true,
            'resource' => Resource::find($id),
        ]);
    }

    /* Fetch resources for a subject */
    public function getFromSubject(?int $subjectId)
    {
        if (!$subjectId) {
            return collect();
        }

        return response()->json([

            'success' => true,
            'resources' => Resource::where('subject_id', $subjectId)->get(),
        ]);
    }

    /* Fetch all resources */
    public function getAll()
    {
        return response()->json([

            'success' => true,
            'resources' => Resource::all(),
        ]);
    }

    /* Fetch recent resources */
    public function getAllRecent()
    {
        return response()->json([

            'success' => true,
            'resources' => Resource::orderByDesc('created_at')->get(),
        ]);
    }

    /* Fetch rated resources */
    public function getAllRated()
    {
        return response()->json([

            'success' => true,
            'resources' => Resource::withAvg('ratings', 'rating')
                ->orderByDesc('ratings_avg_rating')
                ->get(),
        ]);
    }

    /* Fetch recommended resources (uses auth where available) */
    public function getAllRecommended()
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

    /* Fetch page payload (cached) */
    public function getAllPage()
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