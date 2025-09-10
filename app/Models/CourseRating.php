<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function helpful()
    {
        // Helpful votes for this rating (isHelpful = 1)
        return $this->hasMany(Helpful::class, 'course_rating_id')
            ->where('isHelpful', 1);
    }

    public function unhelpful()
    {
        // Unhelpful votes for this rating (isHelpful = 0)
        return $this->hasMany(Helpful::class, 'course_rating_id')
            ->where('isHelpful', 0);
    }

    public function getHelpfulCountAttribute()
    {
        return $this->helpful()->count();
    }

    public function getUnhelpfulCountAttribute()
    {
        return $this->unhelpful()->count();
    }

    public function ratings() {
        return $this->hasMany(CourseRating::class, 'course_id');
    }

    public function getRatingsCountAttribute() {
        return $this->ratings()->count();
    }

    // Ensure rating is always returned with at most two decimal places
    public function getRatingAttribute($value)
    {
        return round($value, 2);
    }

    /**
     * Whether the current authenticated user voted this rating as helpful.
     * Optimized to use preloaded withExists('helpful as is_helpful') when present.
     */
    public function getIsHelpfulAttribute(): ?bool
    {
        // Use precomputed flag if query used withExists alias
        if (array_key_exists('is_helpful', $this->attributes)) {
            return (bool) $this->attributes['is_helpful'];
        }

        $user = Auth::user();
        if (!$user) {
            return null;
        }
        return $this->helpful()->where('user_id', $user->id)->exists();
    }

    /**
     * Whether the current authenticated user voted this rating as unhelpful.
     * Optimized to use preloaded withExists('unhelpful as is_unhelpful') when present.
     */
    public function getIsUnhelpfulAttribute(): ?bool
    {
        // Use precomputed flag if query used withExists alias
        if (array_key_exists('is_unhelpful', $this->attributes)) {
            return (bool) $this->attributes['is_unhelpful'];
        }

        $user = Auth::user();
        if (!$user) {
            return null;
        }
        return $this->unhelpful()->where('user_id', $user->id)->exists();
    }

    protected $appends = ['HelpfulCount', 'UnhelpfulCount', 'ratingsCount', 'isHelpful', 'isUnhelpful'];

}
