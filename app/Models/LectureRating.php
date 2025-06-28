<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LectureRating extends Model
{

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    protected $table = 'lecture_rating';
    
    protected $fillable = [
        'user_id',
        'lecture_id',
        'rating'
    ];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 