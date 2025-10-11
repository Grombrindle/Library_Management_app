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
        'requirements',
        'lecturesCount',
        'subscriptions',
        'image',
        'sources',
        'price',
        'sparkies',
        'sparkiesPrice',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
        // 'sources' => 'array',  <-- removed in favor of explicit accessor/mutator
        'price' => 'string',
        'requirements' => 'array'
    ];

    /**
     * Ensure requirements are displayed as joined string when accessed
     */
    public function getRequirementsAttribute($value)
    {
        // If requirements is already an array (from casting), use it directly
        if (is_array($value)) {
            return implode(' - ', $value);
        }

        // If requirements is a JSON string, decode it first
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return implode(' - ', $decoded);
            }
        }

        // If it's already a string or null, return as is
        return $value;
    }

    /**
     * Accessor for sources - always return as array
     */
    public function getSourcesAttribute($value)
    {
        // dd(json_decode(json_decode($this->attributes['sources'])));
        if (is_null($value)) {
            return [];
        }

        // If it's already an array (unlikely but safe), return it
        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);
        // dd($decoded);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        // If decode failed, return empty array (or you could return [$value])
        return [];
    }

    /**
     * Mutator for sources - accepts array or JSON/string and stores JSON
     */
    public function setSourcesAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['sources'] = null;
            return;
        }

        // If given array => encode to JSON
        if (is_array($value)) {
            $this->attributes['sources'] = json_encode($value, JSON_UNESCAPED_UNICODE);
            return;
        }

        // If given string, check if it's valid JSON already
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // store as-is (already JSON)
                $this->attributes['sources'] = $value;
                return;
            }

            // not valid JSON: store as JSON array containing the string
            $this->attributes['sources'] = json_encode([$value], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Fallback: JSON encode whatever it is
        $this->attributes['sources'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

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

    public function getTeacherNameAttribute()
    {
        $teacher = $this->teacher()->first();
        return $teacher->name;
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

    public function getSubscriptionCountAttribute()
    {
        return $this->users()->count();
    }

    public function getFeaturedRatingsAttribute()
    {// First, get the user's review if it exists
        $userReview = $this->ratings()
            ->whereNotNull('review')
            ->where('isHidden', false)
            ->where('user_id', auth()->id()) // Get current user's review
            ->withCount(['helpful', 'unhelpful'])
            ->first();

        // Get other reviews (excluding user's review) with original sorting
        $otherReviews = $this->ratings()
            ->whereNotNull('review')
            ->where('isHidden', false)
            ->when($userReview, function ($query) {
                $query->where('user_id', '!=', auth()->id()); // Exclude user's review
            })
            ->withCount(['helpful', 'unhelpful'])
            ->orderByDesc('helpful_count')
            ->orderBy('unhelpful_count')
            ->orderByDesc('rating')
            ->orderByRaw('LENGTH(review) DESC')
            ->orderByDesc('created_at')
            ->take($userReview ? 2 : 3) // Take 2 if user review exists, otherwise 3
            ->get();

        // Combine collections
        $withReview = collect();
        if ($userReview) {
            $withReview->push($userReview);
        }
        $withReview = $withReview->merge($otherReviews);

        return $withReview->map(function ($review) {
            $review->user_name = $review->user ? $review->user->userName : null;
            return $review;
        });
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
            ->whereNotNull('file_pdf')
            ->count();
    }

    public function getTotalPdfPagesAttribute()
    {
        return $this->lectures()
            ->whereNotNull('file_pdf')
            ->sum('pages');
    }

    public function getDurationAttribute()
    {
        $lectures = $this->lectures()->where('type', 1)->get();
        $sum = 0;
        foreach ($lectures as $lecture) {
            $sum += $lecture->duration;
        }
        return $sum;
    }

    public function getDurationFormattedAttribute()
    {
        return \App\Models\Lecture::formatDuration($this->duration);
    }

    public function getDurationFormattedLongAttribute()
    {
        return \App\Models\Lecture::formatDurationLong($this->duration);
    }

    public function getDurationHumanAttribute()
    {
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

    public function getRatingsCountAttribute()
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

    public function getFirstRatingsAttribute()
    {
        return $this->ratings()->orderByDesc('rating')->take(3)->get();
    }

    public function getLectureNumAttribute()
    {
        return $this->lectures()->get()->count();
    }

    public function getIsFavoriteAttribute()
    {
        if (Auth::user()) {
            return Auth::user()->favoriteCourses()->where('course_id', $this->id)->exists();
        }
    }

    protected $appends = [
        'rating',
        'isFavorite',
        'subscription_count',
        'rating_breakdown',
        'FeaturedRatings',
        'lectureNum',
        'teacherName',
        'video_lectures_count',
        'pdf_lessons_count',
        'total_pdf_pages',
        'duration',
        'duration_formatted',
        'duration_formatted_long',
        'duration_human',
        'user_rating'
    ];

    public function courseRequest()
    {
        return $this->hasOne(CourseRequest::class);
    }
}
