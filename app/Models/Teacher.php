<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    protected $fillable = [
        'name',
        'userName',
        'description',
        'countryCode',
        'number',
        'password',
        'image',
        'major',
        'links',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
        'links' => 'array'
    ];
    public function toArray()
    {
        $array = parent::toArray();

        // Handle links array merging
        if (isset($array['links'])) {
            // If links is already an array (from casting), use it directly
            if (is_array($array['links'])) {
                $array = array_merge($array, $array['links']);
                unset($array['links']);
            }
            // If links is a JSON string, decode it first
            elseif (is_string($array['links'])) {
                $decodedLinks = json_decode($array['links'], true);
                if (is_array($decodedLinks)) {
                    $array = array_merge($array, $decodedLinks);
                    unset($array['links']);
                }
            }
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
        $avgRating = $this->ratings()->avg('rating');
        return $avgRating ? round($avgRating, 2) : null;
    }
    public function getRatingsCountAttribute()
    {
        return $this->ratings()->count();
    }


    function getCourseNamesAttribute()
    {
        $courses = $this->courses()->pluck('name');
        $coursesNames = "";
        foreach ($courses as $course) {
            $coursesNames .= $course;
            if($course)
                $coursesNames .= " - ";
        }
        return rtrim($coursesNames, ' - ');
    }
    function getCoursesNumAttribute()
    {
        return $this->courses()->count();
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

    public function getUserRatingAttribute()
{
    $user = Auth::user();
    if (!$user || !($user instanceof \App\Models\User)) {
        return null;
    }

    $rating = $user->teacherRatings()->where('teacher_id', $this->id)->first();
    return $rating ? $rating->rating : null;
}

    protected $appends = ['rating', 'courseNames', 'coursesNum', 'rating_breakdown', 'FeaturedRatings', 'UserSubs', 'user_rating', 'ratings_count'];

    public function courseRequests()
    {
        return $this->hasMany(CourseRequest::class);
    }

}
