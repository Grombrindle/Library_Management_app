<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'teacher_id',
        'subject_id',
        'lecturesCount',
        'subscriptions',
        'image',
        'sources',
        'rating',
        'subscription_count',
        'ratings_count',           // NEW
        'video_lectures_count',    // NEW
        'pdf_lessons_count',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'sources' => 'array'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    function users()
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function lectures()
    {
        return $this->hasMany(Lecture::class, 'course_id');
    }

    public function ratings()
    {
        return $this->hasMany(CourseRating::class);
    }

    public function getRatingAttribute()
    {
        return $this->ratings()->avg('rating');
    }

    public function getSubscriptionCountAttribute()
    {
        return $this->users()->count();
    }
    public function getRatingBreakdownAttribute()
    {
        // Get the count of each rating (1-5) for this course
        $breakdown = $this->ratings()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        // Ensure all ratings 1-5 are present, even if 0
        $fullBreakdown = [];
        foreach (range(1, 5) as $rating) {
            $fullBreakdown[$rating] = isset($breakdown[$rating]) ? $breakdown[$rating] : 0;
        }

        return $fullBreakdown;
    }

    public function getFirstRatingsAttribute() {
        return $this->ratings()->orderByDesc('rating')->take(3)->get();
    }

    public function getFeaturedRatingsAttribute()
    {
        // First, get reviews with non-null review text, ordered by IMDB-like algorithm
        $withReview = $this->ratings()
            ->with('user')
            ->whereNotNull('review')
            ->orderByDesc('rating')
            ->orderByRaw('LENGTH(review) DESC')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        // If we have 3, return them (with user name)
        if ($withReview->count() >= 3) {
            return $withReview->map(function($review) {
                $review->user_name = $review->user ? $review->user->userName : null;
                return $review;
            });
        }

        // Otherwise, get more ratings (regardless of review text) to fill up to 3
        $needed = 3 - $withReview->count();
        $withoutReview = $this->ratings()
            ->whereNull('review')
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->take($needed)
            ->get();

        $all = $withReview->concat($withoutReview);
        return $all->map(function($review) {
            $review->user_name = $review->user ? $review->user->userName : null;
            return $review;
        });
    }

    public function getLectureNumAttribute() {
        return $this->lectures()->get()->count();
    }

    protected $appends = ['rating', 'subscription_count', 'rating_breakdown', 'FeaturedRatings', 'lectureNum'];

    // protected $with = ['ratings'];
}
