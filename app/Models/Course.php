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
        return $this->belongsToMany(Lecture::class, 'lecture_course');
    }
}
