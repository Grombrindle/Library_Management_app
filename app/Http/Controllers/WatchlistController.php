<?php

namespace App\Http\Controllers;

use App\Services\WatchlistService;

class WatchlistController extends Controller
{
    protected $watchlistService;

    public function __construct(WatchlistService $watchlistService)
    {
        $this->watchlistService = $watchlistService;
    }

    public function fetchLectures()
    {
        return response()->json([
            'success' => true,
            'watchlist' => $this->watchlistService->fetchLectures()
        ]);
    }

    public function fetchCourses()
    {
        return response()->json([
            'success' => true,
            'watchlist' => $this->watchlistService->fetchCourses()
        ]);
    }

    public function fetchResources()
    {
        return response()->json([
            'success' => true,
            'watchlist' => $this->watchlistService->fetchResources()
        ]);
    }

    public function toggleLecture($id)
    {
        $result = $this->watchlistService->toggleLecture($id);

        return response()->json([
            'success' => true,
            'action' => $result['action'],
            'watchlist' => $result['watchlist']
        ]);
    }

    public function toggleCourse($id)
    {
        $result = $this->watchlistService->toggleCourse($id);

        return response()->json([
            'success' => true,
            'action' => $result['action'],
            'watchlist' => $result['watchlist']
        ]);
    }

    public function toggleResource($id)
    {
        $result = $this->watchlistService->toggleResource($id);

        return response()->json([
            'success' => true,
            'action' => $result['action'],
            'watchlist' => $result['watchlist']
        ]);
    }
}