<?php

namespace App\Services;

use App\Models\Resource;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use App\Actions\Resources\FetchResourceAction;
use App\Actions\Resources\FetchFromSubjectAction;
use App\Actions\Resources\FetchAllResourcesAction;
use App\Actions\Resources\FetchAllRecentResourcesAction;
use App\Actions\Resources\FetchAllRatedResourcesAction;
use App\Actions\Resources\FetchAllRecommendedResourcesAction;
use App\Actions\Resources\FetchAllPageResourcesAction;
use App\Actions\Resources\FetchResourceRatingsAction;
use App\Actions\Resources\RateResourceAction;
use App\Actions\Resources\AddResourceAction;
use App\Actions\Resources\EditResourceAction;
use App\Actions\Resources\DeleteResourceAction;

class ResourceService
{
    public function __construct(
        protected FetchResourceAction $fetchResource,
        protected FetchFromSubjectAction $fetchFromSubject,
        protected FetchAllResourcesAction $fetchAll,
        protected FetchAllRecentResourcesAction $fetchAllRecent,
        protected FetchAllRatedResourcesAction $fetchAllRated,
        protected FetchAllRecommendedResourcesAction $fetchAllRecommended,
        protected FetchAllPageResourcesAction $fetchAllPage,
        protected FetchResourceRatingsAction $fetchRatings,
        protected RateResourceAction $rateResource,
        protected AddResourceAction $addResource,
        protected EditResourceAction $editResource,
        protected DeleteResourceAction $deleteResource,
    ) {
    }

    /* Fetch single resource (nullable id handled in action) */
    public function getResource(?int $id)
    {
        return $this->fetchResource->execute($id);
    }

    /* Fetch resources for a subject */
    public function getFromSubject(?int $subjectId)
    {
        return $this->fetchFromSubject->execute($subjectId);
    }

    /* Fetch all resources */
    public function getAll()
    {
        return $this->fetchAll->execute();
    }

    /* Fetch recent resources */
    public function getAllRecent()
    {
        return $this->fetchAllRecent->execute();
    }

    /* Fetch rated resources */
    public function getAllRated()
    {
        return $this->fetchAllRated->execute();
    }

    /* Fetch recommended resources (uses auth where available) */
    public function getAllRecommended()
    {
        return $this->fetchAllRecommended->execute();
    }

    /* Fetch page payload (cached) */
    public function getAllPage()
    {
        return $this->fetchAllPage->execute();
    }

    /* Fetch ratings for a resource */
    public function getRatings(int $resourceId): array
    {
        return $this->fetchRatings->execute($resourceId);
    }

    /* Create/update rating for a resource */
    public function rate(int $resourceId, int $rating, ?string $review = null): array
    {
        return $this->rateResource->execute([
            'resource_id' => $resourceId,
            'rating' => $rating,
            'review' => $review,
        ]);
    }

    public function ensureDirectoriesExist()
    {
        $dirs = [
            public_path('Images/Resources'),
            public_path('Files/Resources'),
            public_path('Files/Resources/Audio')
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
        }
    }

    public function addResource($request)
    {
        $this->ensureDirectoriesExist();

        // Handle image
        $imagePath = 'Images/Resources/default.png';
        if ($request->hasFile('object_image')) {
            $file = $request->file('object_image');
            $imagePath = 'Images/Resources/' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('Images/Resources'), basename($imagePath));
        }

        // Handle PDFs
        $pdfDir = public_path('Files/Resources');
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

        // Handle audio
        $audioPath = null;
        if ($request->hasFile('resource_audio_file')) {
            $audio = $request->file('resource_audio_file');
            $audioName = time() . '_' . $audio->getClientOriginalName();
            $audio->move(public_path('Files/Resources/Audio'), $audioName);
            $audioPath = 'Files/Resources/Audio/' . $audioName;
        }

        $resource = new Resource();
        $resource->name = $request->input('resource_name');
        $resource->description = $request->input('resource_description');
        $resource->subject_id = $request->input('resource_subject_id');
        $resource->{'publish date'} = $request->input('resource_publish_date');
        $resource->author = $request->input('resource_author');
        $resource->pdf_files = json_encode($pdfFiles);
        $resource->audio_file = $audioPath;
        $resource->image = $imagePath;
        $resource->literaryOrScientific = $resource->subject->literaryOrScientific;
        $resource->save();

        return $resource;
    }

    public function editResource($request, $resource)
    {
        $this->ensureDirectoriesExist();

        // Handle PDFs
        $pdfDir = public_path('Files/Resources');
        $pdfFiles = $resource->pdf_files ? json_decode($resource->pdf_files, true) : [];
        foreach (['ar', 'en', 'es', 'de', 'fr'] as $lang) {
            $input = 'pdf_' . $lang;
            if ($request->hasFile($input)) {
                $pdf = $request->file($input);
                $pdfName = time() . '_' . $lang . '_' . $pdf->getClientOriginalName();
                $pdf->move($pdfDir, $pdfName);
                $pdfFiles[$lang] = 'Files/Resources/' . $pdfName;
            }
        }

        // Handle image
        if ($request->hasFile('object_image')) {
            $file = $request->file('object_image');
            $imagePath = 'Images/Resources/' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('Images/Resources'), basename($imagePath));
            if ($resource->image != 'Images/Resources/default.png' && file_exists(public_path($resource->image))) {
                unlink(public_path($resource->image));
            }
            $resource->image = $imagePath;
        }

        // Handle audio
        if ($request->hasFile('resource_audio_file')) {
            $audio = $request->file('resource_audio_file');
            $audioName = time() . '_' . $audio->getClientOriginalName();
            $audio->move(public_path('Files/Resources/Audio'), $audioName);
            $resource->audio_file = 'Files/Resources/Audio/' . $audioName;
        }
        $resource->name = $request->input('resource_name');
        $resource->description = $request->input('resource_description');
        $resource->literaryOrScientific = $resource->subject->literaryOrScientific;
        $resource->{'publish date'} = $request->input('resource_publish_date');
        $resource->author = $request->input('resource_author');
        $resource->pdf_files = json_encode($pdfFiles);
        $resource->save();

        return $resource;
    }

    public function deleteResource($resource)
    {
        // Delete image
        if ($resource->image != "Images/Resources/default.png" && file_exists(public_path($resource->image))) {
            unlink(public_path($resource->image));
        }

        // Delete PDFs
        if ($resource->pdf_files) {
            $pdfFiles = json_decode($resource->pdf_files, true);
            foreach ($pdfFiles as $file) {
                if ($file && $file !== 'Files/Resources/default.pdf' && file_exists(public_path($file))) {
                    unlink(public_path($file));
                }
            }
        }

        // Delete audio
        if ($resource->audio_file && $resource->audio_file != 'Files/Resources/Audio/default.mp3' && file_exists(public_path($resource->audio_file))) {
            unlink(public_path($resource->audio_file));
        }

        $resource->delete();
    }
}