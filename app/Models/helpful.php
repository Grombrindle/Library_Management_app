<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class helpful extends Model
{
    /** @use HasFactory<\Database\Factories\HelpfulFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lecture_rating_id',
        'teacher_rating_id',
        'course_rating_id',
        'resource_rating_id',
        'isHelpful'
    ];

    public function lectureRating() {
        return $this->belongsTo(Lecture::class);
    }
    public function teacherRating() {
        return $this->belongsTo(Teacher::class);
    }
    public function courseRating() {
        return $this->belongsTo(Course::class);
    }
}
