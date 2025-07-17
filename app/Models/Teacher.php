<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $userName
 * @property string $countryCode
 * @property string $number
 * @property string $password
 * @property string $image
 * @property string|null $links
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $favoritedByUsers
 * @property-read int|null $favorited_by_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quiz> $quizzes
 * @property-read int|null $quizzes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subject> $subjects
 * @property-read int|null $subjects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\university> $universities
 * @property-read int|null $universities_count
 * @method static \Database\Factories\TeacherFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereUserName($value)
 * @mixin \Eloquent
 */
class Teacher extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'links' => 'array'
    ];
    public function toArray()
    {
        $array = parent::toArray();

        // If links is present and is an array, merge it into the top-level array
        if (isset($array['links']) && is_array($array['links'])) {
            $array = array_merge($array, $array['links']);
            unset($array['links']);
        }

        return $array;
    }
    protected $hidden = ['password', 'userName'];


    function courses()
    {
        return $this->hasMany(Course::class);
    }

    function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject');
    }

    public function universities()
    {
        return $this->belongsToMany(university::class, 'teacher_university');
    }
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favourites')
            ->withTimestamps();
    }

    public function ratings()
    {
        return $this->hasMany(TeacherRating::class);
    }

    public function getRatingAttribute()
    {
        return $this->ratings()->avg('rating');
    }

    function getCoursesAttribute()
    {
        return $this->courses()->get()->pluck('name');
    }
    function getCoursesNumAttribute()
    {
        return $this->courses()->count();
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
    public function getUserSubsAttribute()
    {
        // Get all course IDs for this teacher
        $courseIds = $this->courses()->pluck('id');

        // Get unique user IDs from the subscriptions table for these courses
        $uniqueUserCount = \DB::table('subscriptions')
            ->whereIn('course_id', $courseIds)
            ->distinct('user_id')
            ->count('user_id');

        return $uniqueUserCount;
    }

    protected $appends = ['rating', 'courses', 'coursesNum', 'rating_breakdown', 'FeaturedRatings', 'UserSubs'];

}
