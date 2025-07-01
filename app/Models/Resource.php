<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceRating;
use Illuminate\Support\Facades\Auth;

class Resource extends Model
{

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    /** @use HasFactory<\Database\Factories\ResourceFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'literaryOrScientific',
        'subject_id',
        'publish date',
        'image',
        'file',
        'author',
        'created_at',
        'updated_at'
    ];


    public function ratings()
    {
        return $this->hasMany(ResourceRating::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function getRatingAttribute()
    {
        $avgRating = $this->ratings()->avg('rating');
        return $avgRating ? round($avgRating, 2) : null;
    }


    public function getRatingsCountAttribute()
    {
        return $this->ratings()->count();
    }

    public function getRatingBreakdownAttribute()
    {

        $breakdown = $this->ratings()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();


        $fullBreakdown = [];
        foreach (range(1, 5) as $rating) {
            $fullBreakdown[$rating] = isset($breakdown[$rating]) ? $breakdown[$rating] : 0;
        }

        return $fullBreakdown;
    }

    public function getUserRatingAttribute()
    {
        if (!Auth::check()) {
            return null;
        }

        $rating = Auth::user()->resourceRatings()->where('resource_id', $this->id)->first();
        return $rating ? $rating->rating : null;
    }

    public function getFeaturedRatingsAttribute()
    {
        $withReview = $this->ratings()
            ->with('user')
            ->whereNotNull('review')
            ->orderByDesc('rating')
            ->orderByRaw('LENGTH(review) DESC')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        if ($withReview->count() >= 3) {
            return $withReview->map(function($review) {
                $review->user_name = $review->user ? $review->user->userName : null;
                return $review;
            });
        }

        $needed = 3 - $withReview->count();
        $withoutReview = $this->ratings()
            ->with('user')
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

    protected $appends = ['rating', 'rating_breakdown', 'FeaturedRatings', 'ratings_count', 'user_rating'];
}