<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
    public function subject() {
        return $this->belongsTo(Subject::class);
    }
    public function lectures() {
        return $this->hasMany(Lecture::class, 'course_id');
    }
}
