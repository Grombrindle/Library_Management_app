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


        return response()->json([
            'success' => 'true',
            'resource' => $resource
        ]);
    }

    function fetchFromSubject($id)
    {
        $resources = Resource::where('subject_id', $id)
            ->get();

        return response()->json([
            'success' => 'true',
            'resources' => $resources
        ]);
    }

    function fetchAll()
    {
        $resources = Resource::all();

        return response()->json([
            'success' => 'true',
            'resources' => $resources
        ]);
    }
    function fetchAllRecent()
    {
        $resources = Resource::orderByDesc('created_at')->get();

        return response()->json([
            'success' => 'true',
            'resources' => $resources
        ]);
    }
    function fetchAllRated()
    {
        $resources = Resource::withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->get();

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
                ->get();

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
            ->get();

        return response()->json([
            'success' => 'true',
            'resources' => $resources
        ]);


    }
    public function fetchRatings($id)
    {
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
                    'review' => $request->input('review'),
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
        // Ensure directories exist
        $imageDir = 'Images/Resources';
        if (!file_exists(public_path($imageDir))) {
            mkdir(public_path($imageDir), 0755, true);
        }

        // Handle image upload (object_image)
        if (!is_null($request->file('object_image'))) {
            $file = $request->file('object_image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($imageDir), $filename);
            $imagePath = $imageDir . '/' . $filename;
        } else {
            $imagePath = 'Images/Resources/default.png';
        }

        $validated = $request->validate([
            'resource_name' => 'required|string|max:255',
            'resource_description' => 'nullable|string',
            'resource_subject_id' => 'required|integer|exists:subjects,id',
            'resource_publish_date' => 'required|date',
            'resource_author' => 'required|string|max:255',
            'resource_pdf_file' => 'required|file|mimes:pdf',
            'resource_audio_file' => 'nullable|file|mimetypes:audio/mpeg,audio/mp3,audio/wav,audio/x-wav,audio/x-m4a',
            'resource_image' => 'nullable|image|max:2048',
        ]);

        // PDF
        $pdfDir = public_path('Files/Resources');
        if (!file_exists($pdfDir))
            mkdir($pdfDir, 0755, true);
        $pdfPath = null;
        if ($request->hasFile('resource_pdf_file')) {
            $pdf = $request->file('resource_pdf_file');
            $pdfName = time() . '_' . $pdf->getClientOriginalName();
            $pdf->move($pdfDir, $pdfName);
            $pdfPath = 'Files/Resources/' . $pdfName;
        }

        // Audio
        $audioDir = public_path('Files/Resources/Audio');
        if (!file_exists($audioDir))
            mkdir($audioDir, 0755, true);
        $audioPath = null;
        if ($request->hasFile('resource_audio_file')) {
            $audio = $request->file('resource_audio_file');
            $audioName = time() . '_' . $audio->getClientOriginalName();
            $audio->move($audioDir, $audioName);
            $audioPath = 'Files/Resources/Audio/' . $audioName;
        }

        $resource = new Resource();
        $resource->name = $validated['resource_name'];
        $resource->description = $validated['resource_description'];
        $resource->subject_id = $validated['resource_subject_id'];
        $resource->{'publish date'} = $validated['resource_publish_date'];
        $resource->author = $validated['resource_author'];
        $resource->pdf_file = $pdfPath;
        $resource->audio_file = $audioPath;
        $resource->image = $imagePath;
        $resource->literaryOrScientific = $resource->subject->literaryOrScientific;
        $resource->save();

        $data = ['element' => 'resource', 'id' => $resource->id, 'name' => $resource->name];
        session(['add_info' => $data]);
        session(['link' => '/resources']);
        return redirect()->route('add.confirmation');
    }

    public function edit(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);
        $validated = $request->validate([
            'resource_name' => 'required|string|max:255',
            'resource_description' => 'nullable|string',
            'resource_publish_date' => 'required|date',
            'resource_author' => 'required|string|max:255',
            'resource_image' => 'nullable|image|max:2048',
        ]);

        $resource->name = $validated['resource_name'];
        $resource->description = $validated['resource_description'];
        $resource->literaryOrScientific = $resource->subject->literaryOrScientific;
        $resource->{'publish date'} = $validated['resource_publish_date'];
        $resource->author = $validated['resource_author'];

        // Handle image upload and replacement
        $imageDir = 'Images/Resources';
        if (!file_exists(public_path($imageDir))) {
            mkdir(public_path($imageDir), 0755, true);
        }
        if (!is_null($request->file('object_image'))) {
            $file = $request->file('object_image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($imageDir), $filename);
            $newImagePath = $imageDir . '/' . $filename;
            // Delete old image if it's not the default
            if ($resource->image != 'Images/Resources/default.png' && file_exists(public_path($resource->image))) {
                unlink(public_path($resource->image));
            }
            $resource->image = $newImagePath;
        }

        // PDF
        $pdfDir = public_path('Files/Resources');
        if (!file_exists($pdfDir))
            mkdir($pdfDir, 0755, true);
        if ($request->hasFile('resource_pdf_file')) {
            $pdf = $request->file('resource_pdf_file');
            $pdfName = time() . '_' . $pdf->getClientOriginalName();
            $pdf->move($pdfDir, $pdfName);
            $resource->pdf_file = 'Files/Resources/' . $pdfName;
        }

        // Audio
        $audioDir = public_path('Files/Resources/Audio');
        if (!file_exists($audioDir))
            mkdir($audioDir, 0755, true);
        if ($request->hasFile('resource_audio_file')) {
            $audio = $request->file('resource_audio_file');
            $audioName = time() . '_' . $audio->getClientOriginalName();
            $audio->move($audioDir, $audioName);
            $resource->audio_file = 'Files/Resources/Audio/' . $audioName;
        }

        $resource->save();

        $data = ['element' => 'resource', 'id' => $resource->id, 'name' => $resource->name];
        session(['update_info' => $data]);
        session(['link' => '/resources']);
        return redirect()->route('update.confirmation');
    }

    public function delete($id)
    {
        $resource = Resource::findOrFail($id);
        $name = $resource->name;
        // Delete old image if it's not the default
        if ($resource->image != "Images/Resources/default.png" && file_exists(public_path($resource->image))) {
            unlink(public_path($resource->image));
        }

        if ($resource->pdf_file != "Files/Resources/default.pdf" && file_exists(public_path($resource->file_pdf))) {
            // dd(public_path($resource->pdf_file));
            // dd($resource->file_pdf != "Files/Resources/default.pdf");
            unlink(public_path($resource->pdf_file));
        }

        if (file_exists(public_path($resource->audio_file)) && $resource->audio_file != null && $resource->audio_file != 'Files/Resources/Audio/default.mp3') {
            unlink(public_path($resource->audio_file));
        }

        $resource->delete();

        $data = ['element' => 'resource', 'name' => $name];
        session(['delete_info' => $data]);
        session(['link' => '/resources']);
        return redirect()->route('delete.confirmation');
    }

}
