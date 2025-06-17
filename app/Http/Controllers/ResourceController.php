<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;

class ResourceController extends Controller
{
    //

    function fetch($id) {
        $resource = Resource::findOrFail($id);
        $resource->url = url($resource->file);

        return response()->json([
            'success' => 'true',
            'resource' => $resource
        ]);
    }

    function fetchFromSubject($id) {
        $resources = Resource::where('subject_id', $id)
            ->get()
            ->map(function($resource) {
                $resource->url = url($resource->file);
                return $resource;
            });

        return response()->json([
            'success' => 'true',
            'resources' => $resources
        ]);
    }

    function fetchAll() {
        $resources = Resource::all()->map(function($resource) {
            $resource->url = url($resource->file);
            return $resource;
        });

        return response()->json([
            'success' => 'true',
            'resources' => $resources
        ]);
    }

}
