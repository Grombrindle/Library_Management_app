<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'rating',
        'review',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
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

    public function getIsHelpfulAttribute(): ?bool
    {
        if (array_key_exists('is_helpful', $this->attributes)) {
            return (bool) $this->attributes['is_helpful'];
        }
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        return $this->helpful()->where('user_id', $user->id)->exists();
    }

    public function getIsUnhelpfulAttribute(): ?bool
    {
        if (array_key_exists('is_unhelpful', $this->attributes)) {
            return (bool) $this->attributes['is_unhelpful'];
        }
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        return $this->unhelpful()->where('user_id', $user->id)->exists();
    }

    public function getUsernameAttribute() {
        return $this->user->userName;
    }
    protected $appends = ['helpfulCount', 'unhelpfulCount', 'isHelpful', 'isUnhelpful', 'user_name'];
}
