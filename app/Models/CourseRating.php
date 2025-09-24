<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseRating extends Model
{

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    protected $table = 'course_rating';

    protected $fillable = [
        'user_id',
        'course_id',
        'rating'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
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

    public function ratings() {
        return $this->hasMany(CourseRating::class, 'course_id');
    }

    public function getRatingsCountAttribute() {
        return $this->ratings()->count();
    }

    // Ensure rating is always returned with at most two decimal places
    // public function getRatingAttribute($value)
    // {
    //     return round($value, 2);
    // }

    protected $appends = ['HelpfulCount', 'UnhelpfulCount', 'ratingsCount'];

}
