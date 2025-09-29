<?php



namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

/**
 *
 *
 * @property int $id
 * @property string $userName
 * @property string $countryCode
 * @property string $number
 * @property string $password
 * @property int $isBanned
 * @property int $counter
 * @property string|null $last_screenshot_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $favoriteTeachers
 * @property-read int|null $favorite_teachers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lecture> $lectures
 * @property-read int|null $lectures_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subject> $subjects
 * @property-read int|null $subjects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method \Laravel\Sanctum\PersonalAccessToken|null currentAccessToken()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsBanned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastScreenshotAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUserName($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $fillable = [
        'userName',
        'countryCode',
        'number',
        'password',
        'isBanned',
        'counter',
        'avatar',
        'fcm_token',
        'last_screenshot_at',
        'remember_token',
        'created_at',
        'updated_at'
    ];


    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    function courses()
    {
        return $this->belongsToMany(Course::class, 'subscriptions');
    }

    function lectures()
    {
        return $this->belongsToMany(Lecture::class, 'user_lecture');
    }

    public function favoriteTeachers()
    {
        return $this->belongsToMany(Teacher::class, 'favourites')
            ->withTimestamps();
    }

    public function favoriteCourses()
    {
        return $this->belongsToMany(Course::class, 'favourite_courses')
            ->withTimestamps();
    }
    public function favoriteLectures()
    {
        return $this->belongsToMany(Lecture::class, 'favourite_lectures')
            ->withTimestamps();
    }

    public function toggleFavorite(Teacher $teacher): bool
    {

        if ($this->favoriteTeachers()->where('teacher_id', $teacher->id)->exists()) {
            $this->favoriteTeachers()->detach($teacher);
            return false;
        }

        $this->favoriteTeachers()->attach($teacher);
        return true;
    }


    public function isFavorited(Teacher $teacher): bool
    {
        return $this->favoriteTeachers()->where('teacher_id', $teacher->id)->exists();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function ratings()
    {
        $courseRatings = $this->courseRatings()->with('course')->get()->map(function ($rating) {
            $rating->type = 'course';
            return $rating;
        });

        $lectureRatings = $this->lectureRatings()->with('lecture')->get()->map(function ($rating) {
            $rating->type = 'lecture';
            return $rating;
        });

        $teacherRatings = $this->teacherRatings()->with('teacher')->get()->map(function ($rating) {
            $rating->type = 'teacher';
            return $rating;
        });

        return $courseRatings->concat($lectureRatings)->concat($teacherRatings);
    }

    public function courseRatings()
    {
        return $this->hasMany(CourseRating::class);
    }

    public function lectureRatings()
    {
        return $this->hasMany(LectureRating::class);
    }

    public function teacherRatings()
    {
        return $this->hasMany(TeacherRating::class);
    }

    public function resourceRatings()
    {
        return $this->hasMany(ResourceRating::class);
    }

    public function watchlist()
    {
        return $this->belongsToMany(Lecture::class, 'watchlists', 'user_id', 'lecture_id')
            ->withTimestamps();
    }
    public function courseWatchlist()
    {
        return $this->belongsToMany(Course::class, 'watchlists', 'user_id', 'course_id')
            ->withTimestamps();
    }

    public function resourceWatchlist()
    {
        return $this->belongsToMany(\App\Models\Resource::class, 'watchlists', 'user_id', 'resource_id')->withTimestamps();
    }
}
