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
        'course_comment',
        'resource_comment',
        'resource_rating_id',
        'lecture_rating_id',
        'course_rating_id',
        'reason',
        'reasons',
    ];

    protected $casts = [
        'reasons' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by_id');
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
