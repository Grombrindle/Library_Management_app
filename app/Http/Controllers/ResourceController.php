<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use Illuminate\Support\Facades\Cache;
use App\Services\ResourceRatingsService;
use App\Services\ResourceService;
use App\Actions\Resources\{
    AddResourceAction,
    EditResourceAction,
    DeleteResourceAction
};

class ResourceController extends Controller
{
    function fetch($id)
    {
        return app(ResourceService::class)->getResource($id);
    }

    function fetchFromSubject($id)
    {
        return app(ResourceService::class)->getFromSubject($id);
    }

    function fetchAll()
    {
        return app(ResourceService::class)->getAll();
    }
    function fetchAllRecent()
    {
        return app(ResourceService::class)->getAllRecent();
    }
    function fetchAllRated()
    {
        return app(ResourceService::class)->getAllRated();
    }

    public function fetchAllPage()
    {
        return app(ResourceService::class)->getAllPage();
    }

    public function fetchRatings($id)
    {
        return app(ResourceRatingsService::class)->getRatings($id);
    }

    public function rate($id, Request $request)
    {
        $rating = $request->rating;
        $review = $request->review ?? null;

        return app(ResourceRatingsService::class)->rate($id, $rating, $review);
    }


    public function fetchFeaturedRatings($id)
    {
        $resource = Resource::find($id);

        return response()->json([
            'success' => true,
            'FeaturedRatings' => $resource->getFeaturedRatingsAttribute(),
        ]);

    }


    public function addResource(Request $request)
    {
        return app(AddResourceAction::class)->execute($request);
    }

    public function editResource(Request $request, int $resourceId)
    {
        return app(EditResourceAction::class)->execute($request, $resourceId);
    }


    public function delete($id)
    {
        return app(DeleteResourceAction::class)->execute($id);
    }

}