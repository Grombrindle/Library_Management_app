<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

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

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    /** @use HasFactory<\Database\Factories\LectureFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'file_360',
        'file_720',
        'file_1080',
        'file_pdf',
        'description',
        'image',
        'duration',
        'pages',
        'type',
        'views',
        'quiz_id',
        'course_id',
        'created_at',
        'updated_at'
    ];

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

    /**
     * Get formatted video duration in MM:SS format
     * @return string
     */
    public function getFormattedDurationAttribute()
    {
        return self::formatDuration($this->duration);
    }

    /**
     * Get formatted video duration in HH:MM:SS format for longer videos
     * @return string
     */
    public function getFormattedDurationLongAttribute()
    {
        return self::formatDurationLong($this->duration);
    }

    /**
     * Get human readable duration (e.g., "15 min 30 sec")
     * @return string
     */
    public function getHumanDurationAttribute()
    {
        return self::formatDurationHuman($this->duration);
    }

    /**
     * Convert seconds to MM:SS format
     * @param int|float $seconds
     * @return string
     */
    public static function formatDuration($seconds)
    {
        if ($seconds <= 0) {
            return '00:00';
        }

        $minutes = floor($seconds / 60);
        $remainingSeconds = floor($seconds % 60);

        return sprintf('%02d:%02d', $minutes, $remainingSeconds);
    }

    /**
     * Convert seconds to HH:MM:SS format for longer videos
     * @param int|float $seconds
     * @return string
     */
    public static function formatDurationLong($seconds)
    {
        if ($seconds <= 0) {
            return '00:00:00';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = floor($seconds % 60);

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
    }

    /**
     * Convert seconds to human readable format (e.g., "15 min 30 sec")
     * @param int|float $seconds
     * @return string
     */
    public static function formatDurationHuman($seconds)
    {
        if ($seconds <= 0) {
            return '0 seconds';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = floor($seconds % 60);

        $parts = [];

        if ($hours > 0) {
            $parts[] = $hours . ' hour' . ($hours > 1 ? 's' : '');
        }

        if ($minutes > 0) {
            $parts[] = $minutes . ' min' . ($minutes > 1 ? 's' : '');
        }

        if ($remainingSeconds > 0 || empty($parts)) {
            $parts[] = $remainingSeconds . ' sec' . ($remainingSeconds > 1 ? 's' : '');
        }

        return implode(' ', $parts);
    }

    public function getPdfPages()
    {
        return $this->pages;
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    public function ratings()
    {
        return $this->hasMany(LectureRating::class, 'lecture_id');
    }

    public function getRatingAttribute()
    {
        $avgRating = $this->ratings()->avg('rating');
        return $avgRating ? round($avgRating, 2) : null;
    }

    public function getFeaturedRatingsAttribute()
    {
        // Get ratings with review, order by helpful count desc, unhelpful count asc, then rating desc, then review length desc, then created_at desc
        $withReview = $this->ratings()
            ->whereNotNull('review')
            ->withCount(['helpful', 'unhelpful'])
            ->orderByDesc('helpful_count')
            ->orderBy('unhelpful_count')
            ->orderByDesc('rating')
            ->orderByRaw('LENGTH(review) DESC')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();


        if ($withReview->count() >= 3) {
            return $withReview;
        }


        $needed = 3 - $withReview->count();
        $withoutReview = $this->ratings()
            ->whereNull('review')
            ->withCount(['helpful', 'unhelpful'])
            ->orderByDesc('helpful_count')
            ->orderBy('unhelpful_count')
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->take($needed)
            ->get();


        return $withReview->concat($withoutReview);
    }
public function getRatingsCountAttribute() {
        return $this->ratings()->count();
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

    public function getUserRatingAttribute()
    {
        if (!Auth::check()) {
            return null;
        }

        $rating = Auth::user()->lectureRatings()->where('lecture_id', $this->id)->first();
        return $rating ? $rating->rating : null;
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function getLikesAttribute()
    {
        return $this->likes()->where('isLiked', true)->count();
    }

    public function getDislikesAttribute()
    {
        return $this->likes()->where('isDisliked', true)->count();
    }

    public function getIsLikedAttribute() {
        $like = $this->likes()->where('isLiked', true)->where('user_id', Auth::id())->first();
        return $like ? true : false;
    }

    public function getIsDislikedAttribute() {
        $like = $this->likes()->where('isDisliked', true)->where('user_id', Auth::id())->first();
        return $like ? true : false;
    }

    protected $appends = [
        'rating',
        'ratingsCount',
        'FeaturedRatings',
        'rating_breakdown',
        'formatted_duration',
        'formatted_duration_long',
        'human_duration',
        'user_rating',
        'likes',
        'dislikes',
        'isLiked',
        'isDisliked'
    ];
}
