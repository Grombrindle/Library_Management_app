<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Lectures\LectureService;

class LectureController extends Controller
{
    protected LectureService $lectureService;

    public function __construct(LectureService $lectureService)
    {
        $this->lectureService = $lectureService;
    }

    /**
     * Fetch a lecture by ID
     */
    public function fetch($id)
    {
        $lecture = $this->lectureService->getLecture($id);

        if (!$lecture) {
            return response()->json([
                'success' => false,
                'message' => 'Lecture not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'lecture' => $lecture
        ]);
    }

    /**
     * Fetch ratings for a lecture
     */
    public function fetchRatings($id)
    {
        $ratings = $this->lectureService->getRatings($id);

        return response()->json([
            'success' => true,
            'ratings' => $ratings
        ]);
    }

    /**
     * Rate a lecture
     */
    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|max:250'
        ]);

        $rating = $this->lectureService->rate($id, $request->input('rating'), $request->input('review'));

        return response()->json([
            'success' => true,
            'rating' => $rating
        ]);
    }

    /**
     * Increment lecture views
     */
    public function incrementViews($id)
    {
        $lecture = $this->lectureService->incrementViews($id);

        if (!$lecture) {
            return response()->json([
                'success' => false,
                'message' => 'Lecture not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'views' => $lecture->views
        ]);
    }

    /**
     * Fetch quiz questions for a lecture
     */
    public function fetchQuizQuestions($id)
    {
        $questions = $this->lectureService->getQuizQuestions($id);

        return response()->json([
            'success' => true,
            'questions' => $questions
        ]);
    }
}