<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;

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
        'price',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
        'sources' => 'array',
        'price' => 'string',
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
        $avgRating = $this->ratings()->avg('rating');
        return $avgRating ? round($avgRating, 2) : null;
    }

    public function getSubscriptionCountAttribute()
    {
        return $this->users()->count();
    }

    //dis new
    public function getRatingsCountAttribute()
    {
        return $this->ratings()->count();
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

    public function getVideoLecturesCountAttribute()
    {
        return $this->lectures()
            ->where(function ($query) {
                $query->whereNotNull('file_360')
                    ->orWhereNotNull('file_720')
                    ->orWhereNotNull('file_1080');
            })
            ->count();
    }

    public function getPdfLessonsCountAttribute()
    {
        return $this->lectures()
            ->whereNotNull('pdf_file')
            ->count();
    }

    public function getDurationAttribute() {
        $lectures = $this->lectures()->where('type', 1)->get();
        $sum = 0;
        foreach($lectures as $lecture) {
            $sum += $lecture->duration;
        }
        return $sum;
    }

    public function getDurationFormattedAttribute() {
        return \App\Models\Lecture::formatDuration($this->duration);
    }

    public function getDurationFormattedLongAttribute() {
        return \App\Models\Lecture::formatDurationLong($this->duration);
    }

    public function getDurationHumanAttribute() {
        return \App\Models\Lecture::formatDurationHuman($this->duration);
    }

    public function getUserRatingAttribute()
    {
        if (!Auth::check()) {
            return null;
        }

        $rating = Auth::user()->courseRatings()->where('course_id', $this->id)->first();
        return $rating ? $rating->rating : null;
    }

    protected $appends = [
        'rating',
        'subscription_count',
        'ratings_count',
        'video_lectures_count',
        'pdf_lessons_count',
        'duration',
        'duration_formatted',
        'duration_formatted_long',
        'duration_human',
        'user_rating',
        'FeaturedRatings'
    ];


    // protected $with = ['ratings'];
}
