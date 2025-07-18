<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'teacher_id',
        'name',
        'description',
        'subject_id',
        'image',
        'sources',
        'price',
        'status',
        'admin_id',
        'course_id',
        'rejection_reason',
        'lecturesCount',
        'subscriptions',
<<<<<<< HEAD
=======
        'requirements',
>>>>>>> a239985f5d0e6f8a5ad9a53b67fa56104e903321
    ];

    protected $casts = [
        'sources' => 'array',
<<<<<<< HEAD
=======
        'requirements' => 'array',
>>>>>>> a239985f5d0e6f8a5ad9a53b67fa56104e903321
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
