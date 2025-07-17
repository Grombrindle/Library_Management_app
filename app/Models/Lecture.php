<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string|null $file_360
 * @property string|null $file_720
 * @property string|null $file_1080
 * @property string|null $description
 * @property string $image
 * @property int $subject_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Subject $subject
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\LectureFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereFile1080($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereFile360($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereFile720($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Lecture extends Model
{
    /** @use HasFactory<\Database\Factories\LectureFactory> */
    use HasFactory;
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    function users()
    {
        return $this->belongsToMany(User::class, 'user_lecture');
    }

    public function LecturesfavoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favourite_lectures')
            ->withTimestamps();
    }

    public function getVideoLength()
    {
        return $this->duration;
    }

    public function getPdfPages()
    {
        return $this->pages;
    }

    public function quiz() {
        return $this->hasOne(Quiz::class);
    }

    public function ratings() {
        return $this->hasMany(LectureRating::class);
    }

    public function getRatingAttribute() {
        return $this->ratings()->avg('rating');
    }

    public function getFeaturedRatingsAttribute()
    {
        
        $withReview = $this->ratings()
            ->whereNotNull('review')
            ->orderByDesc('rating')
            ->orderByRaw('LENGTH(review) DESC')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        // If we have 3, return them
        if ($withReview->count() >= 3) {
            return $withReview;
        }

        // Otherwise, get more ratings (regardless of review text) to fill up to 3
        $needed = 3 - $withReview->count();
        $withoutReview = $this->ratings()
            ->whereNull('review')
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->take($needed)
            ->get();

        // Merge and return
        return $withReview->concat($withoutReview);
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
    protected $appends = ['rating', 'FeaturedRatings', 'rating_breakdown'];
}
