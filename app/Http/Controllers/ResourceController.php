<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    //

    function fetch($id)
    {
        $resource = Resource::findOrFail($id);
        $resource->url = url($resource->file);

        return response()->json([
            'success' => 'true',
            'resource' => $resource
        ]);
    }

    function fetchFromSubject($id)
    {
        $resources = Resource::where('subject_id', $id)
            ->get()
            ->map(function ($resource) {
                $resource->url = url($resource->file);
                return $resource;
            });

        return response()->json([
            'success' => 'true',
            'resources' => $resources
        ]);
    }

    function fetchAll()
    {
        $resources = Resource::all()->map(function ($resource) {
            $resource->url = url($resource->file);
            return $resource;
        });

        return response()->json([
            'success' => 'true',
            'resources' => $resources
        ]);
    }
    function fetchAllRecent()
    {
        $resources = Resource::orderByDesc('created_at')
            ->get()
            ->map(function ($resource) {
                $resource->url = url($resource->file);
                return $resource;
            });

        return response()->json([
            'success' => 'true',
            'resources' => $resources
        ]);
    }
    function fetchAllRated()
    {
        $resources = Resource::withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->get()
            ->map(function ($resource) {
                $resource->url = url($resource->file);
                return $resource;
            });

        return response()->json([
            'success' => 'true',
            'resources' => $resources
        ]);
    }
    function fetchAllRecommended()
    {
        // Get the authenticated user
        $user = Auth::user();

        if (!$user) {
            // If no user is authenticated, fall back to basic recommendation
            $resources = Resource::withCount(['users', 'ratings'])
                ->withAvg('ratings', 'rating')
                ->orderByDesc(DB::raw('
                    (
                        (COALESCE(ratings_avg_rating, 0) * 0.6) +
                        (ratings_count * 0.3) +
                        (users_count * 0.1)
                    ) *
                    (1 + (COALESCE(ratings_avg_rating, 0) / 5))
                '))
                ->get()
                ->map(function ($resource) {
                    $resource->url = url($resource->file);
                    return $resource;
                });

            return response()->json([
                'success' => 'true',
                'resources' => $resources
            ]);
        }

        // Get user's subscribed course subjects and their types
        $userSubjectIds = $user->courses()->pluck('subject_id')->unique();
        $userSubjectTypes = DB::table('subjects')
            ->whereIn('id', $userSubjectIds)
            ->pluck('literaryOrScientific')
            ->unique();

        $resources = Resource::withCount(['ratings'])
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
            ->get()
            ->map(function ($resource) {
                $resource->url = url($resource->file);
                return $resource;
            });

        return response()->json([
            'success' => 'true',
            'resources' => $resources
        ]);


    }

    public function fetchRatings($id) {
        $ratings = DB::table('resources_ratings')->where('resource_id', $id)->get();
        return response()->json([
            'ratings' => $ratings
        ]);
    }

    public function rate(Request $request, $id)
    {
        $resource = Resource::find($id);

        if ($resource) {
            $rate = DB::table('resources_ratings')->updateOrInsert(
                [
                    'user_id' => Auth::user()->id,
                    'resource_id' => $id
                ],
                [
                    'rating' => $request->input('rating'),
                    'updated_at' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Rating saved successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Resource not found'
        ], 404);
    }

}
