<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LikeService;

class LikeController extends Controller
{
    public function __construct(
        private LikeService $likeService
    ) {}

    public function fetchLikes(int $id)
    {
        $result = $this->likeService->fetchLikes($id);
        return response()->json($result);
    }

    public function toggleLike(Request $request)
    {
        $validated = $request->validate([
            'lecture_id' => 'required|exists:lectures,id',
        ]);

        $result = $this->likeService->toggleLike($validated['lecture_id']);
        return response()->json($result);
    }

    public function toggleDislike(Request $request)
    {
        $validated = $request->validate([
            'lecture_id' => 'required|exists:lectures,id',
        ]);

        $result = $this->likeService->toggleDislike($validated['lecture_id']);
        return response()->json($result);
    }
}
