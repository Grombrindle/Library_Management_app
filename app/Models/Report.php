<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reporter_id',
        'handled_by_id',
        'lecture_comment',
        'teacher_comment',
        'course_comment',
        'resource_comment',
        'lecture_rating_id',
        'resource_rating_id',
        'teacher_rating_id',
        'course_rating_id',
        'message',
        'reasons',
    ];

    protected $casts = [
        'reasons' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function handler()
    {
        return $this->belongsTo(Admin::class, 'handled_by_id');
    }

    /**
     * Get the type of report (lecture, course, book/resource) based on which comment is filled.
     */
    public function getTypeAttribute()
    {
        if ($this->lecture_comment) {
            return 'lecture';
        } elseif ($this->course_comment) {
            return 'course';
        } elseif ($this->resource_comment) {
            return 'resource';
        }
        return 'other';
    }
}
