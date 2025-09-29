<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LectureService;
use App\Models\Lecture;

class LectureController extends Controller
{
    public function __construct(private LectureService $lectureService)
    {
    }
    public function fetch($lectureId)
    {
        return response()->json($this->lectureService->fetchLecture($lectureId));
    }
    public function fetchRatings($lectureId)
    {
        return response()->json($this->lectureService->getLectureRatings($lectureId));
    }

    public function getCourseLectures($courseId)
    {
        return response()->json($this->lectureService->getCourseLectures($courseId));
    }

    public function getCourseLecturesRated($courseId)
    {
        return response()->json($this->lectureService->getCourseLecturesRated($courseId));
    }

    public function getCourseLecturesRecent($courseId)
    {
        return response()->json($this->lectureService->getCourseLecturesRecent($courseId));
    }

    public function fetchQuizQuestions($lectureId)
    {
        return response()->json($this->lectureService->fetchQuizQuestions($lectureId));
    }



    public function fetchFeaturedRatings($id)
    {
        $lecture = Lecture::find($id);

        if ($lecture)
            return response()->json([
                'success' => true,
                'FeaturedRatings' => $lecture->getFeaturedRatingsAttribute(),
            ]);

        return response()->json([
            'success' => false,
            'message' => 'Lecture not found'
        ], 404);

    }

    public function rate(Request $request, $lectureId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        return response()->json(
            $this->lectureService->rateLecture(
                $lectureId,
                $validated['rating'],
                $validated['review'] ?? null
            )
        );
    }

    public function incrementViews($lectureId)
    {
        return response()->json($this->lectureService->incrementViews($lectureId));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'course' => 'required|integer|exists:courses,id',
            'lecture_name' => 'required|string|max:255',
            'lecture_description' => 'nullable',
            'lecture_file_360' => 'nullable',
            'lecture_file_720' => 'nullable',
            'lecture_file_1080' => 'nullable',
            'lecture_file_pdf' => 'nullable',
        ]);

        return app(lectureService::class)->addLecture($request);
    }

    public function edit(Request $request, $lectureId)
    {
        $validated = $request->validate([
            'lecture_name' => 'nullable|string|max:255',
            'lecture_description' => 'nullable|string',
            'lecture_file_360' => 'nullable',
            'lecture_file_720' => 'nullable',
            'lecture_file_1080' => 'nullable',
            'lecture_file_pdf' => 'nullable',
        ]);

        return app(lectureService::class)->editLecture($request, $lectureId);
    }

    public function delete($lectureId)
    {
        return app(lectureService::class)->deleteLecture($lectureId);
    }

    public function fetchFile($lectureId, $type)
    {
        return response()->json($this->lectureService->fetchLectureFile($lectureId, $type));
    }
}
