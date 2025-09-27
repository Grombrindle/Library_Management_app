<?php

namespace App\Services\Lectures;

use App\Actions\Lectures\FetchLectureAction;
use App\Actions\Lectures\FetchLectureRatingsAction;
use App\Actions\Lectures\RateLectureAction;
use App\Actions\Lectures\IncrementLectureViewsAction;
use App\Actions\Lectures\FetchLectureQuizQuestionsAction;
use App\Models\Lecture;
use App\Models\LectureRating;

class LectureService
{
    public function __construct(
        protected FetchLectureAction $fetchLecture,
        protected FetchLectureRatingsAction $fetchRatings,
        protected RateLectureAction $rateLecture,
        protected IncrementLectureViewsAction $incrementViews,
        protected FetchLectureQuizQuestionsAction $fetchQuizQuestions,
    ) {}

    /**
     * Get a single lecture with relationships.
     */
    public function getLecture(int $lectureId): ?Lecture
    {
        return $this->fetchLecture->execute($lectureId);
    }

    /**
     * Get all ratings for a lecture.
     */
    public function getRatings(int $lectureId): array
    {
        return $this->fetchRatings->execute($lectureId);
    }

    /**
     * Add or update a lecture rating for the authenticated user.
     */
    public function rate(int $lectureId, int $rating, ?String $review): LectureRating
    {
        return $this->rateLecture->execute($lectureId, $rating, $review);
    }

    /**
     * Increment lecture view counter.
     */
    public function incrementViews(int $lectureId): ?Lecture
    {
        return $this->incrementViews->execute($lectureId);
    }

    /**
     * Get quiz questions associated with a lecture.
     */
    public function getQuizQuestions(int $lectureId)
    {
        return $this->fetchQuizQuestions->execute($lectureId);
    }
}