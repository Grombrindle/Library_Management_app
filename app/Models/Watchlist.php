<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lecture;
use App\Models\Course;

class Watchlist extends Model
{
    /** @use HasFactory<\Database\Factories\WatchlistFactory> */
    use HasFactory;
    public function lecture()
    {
        return $this->belongsTo(Lecture::class, 'lecture_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function resource()
    {
        return $this->belongsTo(\App\Models\Resource::class, 'resource_id');
    }

    // If you want to customize the appended attributes:
    public function getLectureAttribute()
    {
        return $this->lecture()->first();
    }

    public function getCourseAttribute()
    {
        return $this->course()->first();
    }

    public function getResourceAttribute()
    {
        return $this->resource()->first();
    }

    protected $appends = ['lecture', 'course', 'resource'];
}
