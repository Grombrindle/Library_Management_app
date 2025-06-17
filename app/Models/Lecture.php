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

    protected $appends = ['rating'];
}
