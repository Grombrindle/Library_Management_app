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

    protected $fillable = [
        'name',
        'userName',
        'countryCode',
        'number',
        'password',
        'image',
        'links',
        'created_at',
        'updated_at'
    ];

    function courses()
    {
        return $this->hasMany(Course::class);
    }
    
    public function requests()
    {
        return $this->hasMany(TeacherRequest::class);
    }

    function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject');
    }

    public function universities()
    {
        return $this->belongsToMany(University::class, 'teacher_university');
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

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }
    public function getFirstSubjectNameAttribute()
    {
        return $this->subjects->first()->name ?? 'General';
    }
}
