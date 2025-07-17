<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;

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
        'sources' => 'array'
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
        return $this->ratings()->avg('rating');
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

    protected $appends = [
        'rating',
        'subscription_count',
        'ratings_count',
        'video_lectures_count',
        'pdf_lessons_count',
    ];


    // protected $with = ['ratings'];
}
