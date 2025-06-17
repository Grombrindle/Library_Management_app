<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'name',
        'description',
        'image',
        'teacher_id',
        'subject_id',
        'lecturesCount',
        'subscriptions',
        'sources'
    ];

    protected $casts = [
        'sources' => 'array'
    ];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
    function users()
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }
    public function subject() {
        return $this->belongsTo(Subject::class);
    }
    public function lectures() {
        return $this->hasMany(Lecture::class, 'course_id');
    }

    public function ratings() {
        return $this->hasMany(CourseRating::class);
    }

    public function getRatingAttribute() {
        return $this->ratings()->avg('rating');
    }

    public function getSubscriptionCountAttribute() {
        return $this->users()->count();
    }

    protected $appends = ['rating', 'subscription_count'];

    // protected $with = ['ratings'];
}
