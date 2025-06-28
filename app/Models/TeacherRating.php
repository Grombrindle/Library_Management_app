<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherRating extends Model
{

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    protected $table = 'teacher_ratings';
    protected $fillable = [
        'user_id',
        'teacher_id',
        'rating'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
