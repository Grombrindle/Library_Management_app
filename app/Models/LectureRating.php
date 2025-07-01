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

    public function helpful() {
        return $this->hasMany(Helpful::class)->where('isHelpful', 1);
    }

    public function unhelpful() {
        return $this->hasMany(Helpful::class)->where('isHelpful', 0);
    }

    public function getHelpfulCountAttribute() {
        return $this->helpful()->count();
    }

    public function getUnhelpfulCountAttribute() {
        return $this->unhelpful()->count();
    }
    public function getRatingAttribute($value)
    {
        return round($value, 2);
    }

    public function getRatingsCountAttribute() {
        return $this->ratings()->count();
    }

    protected $appends = ['HelpfulCount', 'UnhelpfulCount', 'rating', 'ratingsCount'];
}