<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use Illuminate\Support\Facades\Cache;

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

    public function fetchAllPage()
    {

        // Use caching to improve performance for frequently accessed data
        $cacheKey = 'page_resources_' . (Auth::id() ?? 'guest');
        $cacheDuration = 300; // 5 minutes

        return Cache::remember($cacheKey, $cacheDuration, function () {
            // Get recommended Resources (using the existing algorithm) - only essential fields
            // Get user's subscribed course subjects and their types
            $userSubjectIds = Auth::user()->courses()->pluck('subject_id')->unique();
            $userSubjectTypes = DB::table('subjects')
                ->whereIn('id', $userSubjectIds)
                ->pluck('literaryOrScientific')
                ->unique();

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

            // Get top-rated Resources - only essential fields

            $topRatedResources = Resource::withAvg('ratings', 'rating')
                ->orderByDesc('ratings_avg_rating')
                ->get();
            // Get recent Resources - only essential fields
            $recentResources = Resource::orderByDesc('created_at')->get();

            return response()->json([
                'success' => true,
                'scientificSubjects' => Subject::where('literaryOrScientific',1)->select(['id', 'name', 'literaryOrScientific', 'image'])->get()->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'literaryOrScientific' => $subject->literaryOrScientific,
                        'image' => $subject->image,
                        'imageUrl' => url($subject->image),
                    ];
                }),
                'literarySubjects' => Subject::where('literaryOrScientific', 0)->select(['id', 'name', 'literaryOrScientific', 'image'])->get()->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'literaryOrScientific' => $subject->literaryOrScientific,
                        'image' => $subject->image,
                        'imageUrl' => url($subject->image),
                    ];
                }),
                'recommended' => $recommendedResources,
                'top_rated' => $topRatedResources,
                'recent' => $recentResources,
            ]);
        });
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
            'pdf_ar' => 'nullable|file|mimes:pdf',
            'pdf_en' => 'nullable|file|mimes:pdf',
            'pdf_es' => 'nullable|file|mimes:pdf',
            'pdf_de' => 'nullable|file|mimes:pdf',
            'pdf_fr' => 'nullable|file|mimes:pdf',
            'resource_audio_file' => 'nullable|file|mimetypes:audio/mpeg,audio/mp3,audio/wav,audio/x-wav,audio/x-m4a',
            'resource_image' => 'nullable|image|max:2048',
        ]);
        // At least one of Arabic or English is required
        if (!$request->hasFile('pdf_ar') && !$request->hasFile('pdf_en')) {
            return back()->withErrors(['pdf_ar' => __('messages.arabicOrEnglishRequired')])->withInput();
        }

        $pdfDir = public_path('Files/Resources');
        if (!file_exists($pdfDir)) mkdir($pdfDir, 0755, true);
        $pdfFiles = [];
        foreach (['ar', 'en', 'es', 'de', 'fr'] as $lang) {
            $input = 'pdf_' . $lang;
            if ($request->hasFile($input)) {
                $pdf = $request->file($input);
                $pdfName = time() . '_' . $lang . '_' . $pdf->getClientOriginalName();
                $pdf->move($pdfDir, $pdfName);
                $pdfFiles[$lang] = 'Files/Resources/' . $pdfName;
            }
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
        $resource->pdf_files = json_encode($pdfFiles);
        $resource->audio_file = $audioPath ?? null;
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
            'pdf_ar' => 'nullable|file|mimes:pdf',
            'pdf_en' => 'nullable|file|mimes:pdf',
            'pdf_es' => 'nullable|file|mimes:pdf',
            'pdf_de' => 'nullable|file|mimes:pdf',
            'pdf_fr' => 'nullable|file|mimes:pdf',
            'resource_image' => 'nullable|image|max:2048',
        ]);

        $pdfDir = public_path('Files/Resources');
        if (!file_exists($pdfDir)) mkdir($pdfDir, 0755, true);
        $pdfFiles = $resource->pdf_files ?: [];
        foreach (['ar', 'en', 'es', 'de', 'fr'] as $lang) {
            $input = 'pdf_' . $lang;
            if ($request->hasFile($input)) {
                $pdf = $request->file($input);
                $pdfName = time() . '_' . $lang . '_' . $pdf->getClientOriginalName();
                $pdf->move($pdfDir, $pdfName);
                $pdfFiles[$lang] = 'Files/Resources/' . $pdfName;
            }
        }

        if (empty($pdfFiles['ar'])) {
            return back()->withErrors(['pdf_ar' => __('messages.arabicRequired')])->withInput();
        }

        $resource->name = $validated['resource_name'];
        $resource->description = $validated['resource_description'];
        $resource->literaryOrScientific = $resource->subject->literaryOrScientific;
        $resource->{'publish date'} = $validated['resource_publish_date'];
        $resource->author = $validated['resource_author'];
        $resource->pdf_files = json_encode($pdfFiles);

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
        // $pdfDir = public_path('Files/Resources'); // This line is now redundant as $pdfDir is defined above
        // if (!file_exists($pdfDir)) // This line is now redundant as $pdfDir is defined above
        //     mkdir($pdfDir, 0755, true); // This line is now redundant as $pdfDir is defined above
        // if ($request->hasFile('resource_pdf_file')) { // This line is now redundant as $pdfFiles is handled above
        //     $pdf = $request->file('resource_pdf_file'); // This line is now redundant as $pdfFiles is handled above
        //     $pdfName = time() . '_' . $pdf->getClientOriginalName(); // This line is now redundant as $pdfFiles is handled above
        //     $pdf->move($pdfDir, $pdfName); // This line is now redundant as $pdfFiles is handled above
        //     $resource->pdf_file = 'Files/Resources/' . $pdfName; // This line is now redundant as $pdfFiles is handled above
        // }

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

        // Delete all PDF files according to the pdf_files attribute
        if ($resource->pdf_files) {
            $pdfFiles = json_decode(json_encode($resource->pdf_files), true);
            if (is_array($pdfFiles)) {
                foreach ($pdfFiles as $lang => $filePath) {
                    if ($filePath && $filePath !== 'Files/Resources/default.pdf' && file_exists(public_path($filePath))) {
                        unlink(public_path($filePath));
                    }
                }
            }
        }

        // Delete audio file if it's not the default
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
