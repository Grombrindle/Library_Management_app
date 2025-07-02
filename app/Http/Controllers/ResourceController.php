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

    public function add(Request $request)
    {
        $validated = $request->validate([
            'resource_name' => 'required|string|max:255',
            'resource_description' => 'nullable|string',
            'resource_literary_or_scientific' => 'required|integer',
            'resource_subject_id' => 'required|integer|exists:subjects,id',
            'resource_publish_date' => 'required|date',
            'resource_author' => 'required|string|max:255',
            'resource_pdf_file' => 'required|file|mimes:pdf',
            'resource_audio_file' => 'nullable|file|mimetypes:audio/mpeg,audio/mp3,audio/wav,audio/x-wav,audio/x-m4a',
            'resource_image' => 'nullable|image|max:2048',
        ]);

        $pdfPath = $request->file('resource_pdf_file')->store('Files/Resources', 'public');
        $audioPath = $request->file('resource_audio_file') ? $request->file('resource_audio_file')->store('Files/Resources/Audio', 'public') : null;
        $imagePath = $request->file('resource_image') ? $request->file('resource_image')->store('Images/Resources', 'public') : '/Images/Resources/default.png';

        $resource = new Resource();
        $resource->name = $validated['resource_name'];
        $resource->description = $validated['resource_description'];
        $resource->literaryOrScientific = $validated['resource_literary_or_scientific'];
        $resource->subject_id = $validated['resource_subject_id'];
        $resource->{'publish date'} = $validated['resource_publish_date'];
        $resource->author = $validated['resource_author'];
        $resource->pdf_file = '/storage/' . $pdfPath;
        $resource->audio_file = $audioPath ? '/storage/' . $audioPath : null;
        $resource->image = $imagePath;
        $resource->save();

        return redirect('/confirmadd');
    }

    public function edit(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);
        $validated = $request->validate([
            'resource_name' => 'required|string|max:255',
            'resource_description' => 'nullable|string',
            'resource_literary_or_scientific' => 'required|integer',
            'resource_subject_id' => 'required|integer|exists:subjects,id',
            'resource_publish_date' => 'required|date',
            'resource_author' => 'required|string|max:255',
            'resource_pdf_file' => 'nullable|file|mimes:pdf',
            'resource_audio_file' => 'nullable|file|mimetypes:audio/mpeg,audio/mp3,audio/wav,audio/x-wav,audio/x-m4a',
            'resource_image' => 'nullable|image|max:2048',
        ]);

        $resource->name = $validated['resource_name'];
        $resource->description = $validated['resource_description'];
        $resource->literaryOrScientific = $validated['resource_literary_or_scientific'];
        $resource->subject_id = $validated['resource_subject_id'];
        $resource->{'publish date'} = $validated['resource_publish_date'];
        $resource->author = $validated['resource_author'];

        if ($request->hasFile('resource_pdf_file')) {
            $pdfPath = $request->file('resource_pdf_file')->store('Files/Resources', 'public');
            $resource->pdf_file = '/storage/' . $pdfPath;
        }
        if ($request->hasFile('resource_audio_file')) {
            $audioPath = $request->file('resource_audio_file')->store('Files/Resources/Audio', 'public');
            $resource->audio_file = '/storage/' . $audioPath;
        }
        if ($request->hasFile('resource_image')) {
            $imagePath = $request->file('resource_image')->store('Images/Resources', 'public');
            $resource->image = $imagePath;
        }
        $resource->save();

        return redirect('/confirmupdate');
    }

}
