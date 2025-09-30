<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LectureService;

use App\Actions\Lectures\{
    AddLectureAction,
    EditLectureAction,
    DeleteLectureAction
};

class LectureController extends Controller
{
    public function fetch($lectureId)
    {
        return app(LectureService::class)->fetchLecture($lectureId);
    }
    public function fetchRatings($lectureId)
    {
        return app(LectureService::class)->getLectureRatings($lectureId);
    }

    public function getCourseLectures($courseId)
    {
        return app(LectureService::class)->getCourseLectures($courseId);
    }

    public function getCourseLecturesRated($courseId)
    {
        return app(LectureService::class)->getCourseLecturesRated($courseId);
    }

    public function getCourseLecturesRecent($courseId)
    {
        return app(LectureService::class)->getCourseLecturesRecent($courseId);
    }

    public function fetchQuizQuestions($lectureId)
    {
        return app(LectureService::class)->fetchQuizQuestions($lectureId);
    }

    public function fetchFeaturedRatings($lectureId)
    {
        return app(LectureService::class)->getLectureFeaturedRatings($lectureId);
    }

    public function rate(Request $request, $lectureId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        return app(LectureService::class)->rateLecture(
            $lectureId,
            $validated['rating'],
            $validated['review'] ?? null
        );
    }

    public function incrementViews($lectureId)
    {
        return app(LectureService::class)->incrementViews($lectureId);
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

        return app(AddLectureAction::class)->execute($request);
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

        return app(EditLectureAction::class)->execute($request, $lectureId);
    }

    public function delete($lectureId)
    {
        return app(DeleteLectureAction::class)->execute($lectureId);
    }

    public function fetchFile360($lectureId)
    {
        return app(LectureService::class)->fetchLectureFile($lectureId, "360");
    }

    public function fetchFile720($lectureId)
    {
        return app(LectureService::class)->fetchLectureFile($lectureId, "720");
    }

    public function fetchFile1080($lectureId)
    {
        return app(LectureService::class)->fetchLectureFile($lectureId, "1080");
    }

    public function fetchPdf($lectureId)
    {
        return app(LectureService::class)->fetchLectureFile($lectureId, "pdf");
    }
}
