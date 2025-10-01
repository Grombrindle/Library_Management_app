<?php

namespace App\Services;

use App\Models\Lecture;
use App\Models\Course;
use App\Models\LectureRating;
use Illuminate\Support\Facades\Auth;

class LectureService
{

    public function fetchLecture(int $lectureId): array
    {
        $lecture = Lecture::find($lectureId);

        if (!$lecture) {
            return [
                'success' => false,
                'message' => 'Lecture not found'
            ];
        }

        return [
            'success' => true,
            'lecture' => $lecture
        ];
    }


    public function fetchLectureFile(int $lectureId, string $fileType): array
    {
        $lecture = Lecture::find($lectureId);

        if (!$lecture) {
            return [
                'success' => false,
                'message' => 'Lecture not found'
            ];
        }

        $filePath = match ($fileType) {
            '360' => $lecture->file_360,
            '720' => $lecture->file_720,
            '1080' => $lecture->file_1080,
            'pdf' => $lecture->file_pdf,
            default => null
        };

        if (!$filePath) {
            return [
                'success' => false,
                'message' => "File of type {$fileType} not found"
            ];
        }

        return [
            'success' => true,
            'url' => url($filePath)
        ];
    }

    public function getCourseLectures(int $courseId): array
    {
        $course = Course::find($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found'
            ];
        }

        $lectures = $course->lectures()->with('quiz')->get();

        $lectures->each(function ($lecture) {
            if ($lecture->quiz) {
                $score = \App\Models\Score::where('user_id', Auth::id())
                    ->where('quiz_id', $lecture->quiz->id)
                    ->first();
                $lecture->score = $score ? $score->correctAnswers : null;
                $lecture->number_of_questions = $lecture->quiz->questions->count();
            } else {
                $lecture->score = null;
                $lecture->number_of_questions = 0;
            }

            $lecture->url360 = $lecture->file_360 ? url($lecture->file_360) : null;
            $lecture->url720 = $lecture->file_720 ? url($lecture->file_720) : null;
            $lecture->url1080 = $lecture->file_1080 ? url($lecture->file_1080) : null;
            $lecture->urlpdf = $lecture->file_pdf ? url($lecture->file_pdf) : null;
        });

        return [
            'success' => true,
            'lectures' => $lectures,
            'course' => $course,
        ];
    }

    public function getCourseLecturesRated(int $courseId): array
    {
        $course = Course::find($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found'
            ];
        }

        $lectures = $course->lectures()
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->get();

        $lectures->each(function ($lecture) {
            if ($lecture->quiz) {
                $score = \App\Models\Score::where('user_id', Auth::id())
                    ->where('quiz_id', $lecture->quiz->id)
                    ->first();
                $lecture->score = $score ? $score->correctAnswers : null;
                $lecture->number_of_questions = $lecture->quiz->questions->count();
            } else {
                $lecture->score = null;
                $lecture->number_of_questions = 0;
            }

            $lecture->url360 = $lecture->file_360 ? url($lecture->file_360) : null;
            $lecture->url720 = $lecture->file_720 ? url($lecture->file_720) : null;
            $lecture->url1080 = $lecture->file_1080 ? url($lecture->file_1080) : null;
            $lecture->urlpdf = $lecture->file_pdf ? url($lecture->file_pdf) : null;
        });

        return [
            'success' => true,
            'lectures' => $lectures,
            'course' => $course,
        ];
    }

    public function getCourseLecturesRecent(int $courseId): array
    {
        $course = Course::find($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found'
            ];
        }

        $lectures = $course->lectures()
            ->withAvg('ratings', 'rating')
            ->orderByDesc('created_at')
            ->get();

        $lectures->each(function ($lecture) {
            if ($lecture->quiz) {
                $score = \App\Models\Score::where('user_id', Auth::id())
                    ->where('quiz_id', $lecture->quiz->id)
                    ->first();
                $lecture->score = $score ? $score->correctAnswers : null;
                $lecture->number_of_questions = $lecture->quiz->questions->count();
            } else {
                $lecture->score = null;
                $lecture->number_of_questions = 0;
            }

            $lecture->url360 = $lecture->file_360 ? url($lecture->file_360) : null;
            $lecture->url720 = $lecture->file_720 ? url($lecture->file_720) : null;
            $lecture->url1080 = $lecture->file_1080 ? url($lecture->file_1080) : null;
            $lecture->urlpdf = $lecture->file_pdf ? url($lecture->file_pdf) : null;
        });

        return [
            'success' => true,
            'lectures' => $lectures,
            'course' => $course,
        ];
    }

    public function fetchQuizQuestions(int $lectureId): array
    {
        $lecture = Lecture::with('quiz.questions')->find($lectureId);

        if (!$lecture) {
            return [
                'success' => false,
                'reason' => 'Lecture not Found'
            ];
        }

        if (!$lecture->quiz) {
            return [
                'success' => false,
                'reason' => 'No Quiz For This Lesson'
            ];
        }

        return [
            'success' => true,
            'quiz' => $lecture->quiz
        ];
    }

    public function rateLecture(int $lectureId, int $rating, ?string $review = null): array
    {
        $lecture = Lecture::find($lectureId);

        if (!$lecture) {
            return [
                'success' => false,
                'message' => 'Lecture not found'
            ];
        }

        $lectureRating = LectureRating::updateOrCreate(
            [
                'lecture_id' => $lecture->id,
                'user_id' => Auth::id()
            ],
            [
                'rating' => $rating,
                'review' => $review
            ]
        );

        return [
            'success' => true,
            'rating' => $lectureRating->rating,
            'review' => $lectureRating->review,
        ];
    }

    public function incrementViews(int $lectureId): array
    {
        $lecture = Lecture::find($lectureId);

        if (!$lecture) {
            return [
                'success' => false,
                'message' => 'Lecture not found'
            ];
        }

        $lecture->increment('views');

        return [
            'success' => true,
            'views' => $lecture->views
        ];
    }

    public function getLectureRatings(int $lectureId)
    {
        $lecture = Lecture::with('ratings')->find($lectureId);

        if (!$lecture) {
            return [];
        }

        return response()->json([
            'success' => true,
            'ratings' => $lecture->ratings
        ]);
    }


    public function getLectureFeaturedRatings(int $lectureId)
    {
        $lecture = Lecture::find($lectureId);

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
}
