<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseRating extends Model
{
    protected $table = 'course_rating';
    
    protected $fillable = [
        'user_id',
        'course_id',
        'rating'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 