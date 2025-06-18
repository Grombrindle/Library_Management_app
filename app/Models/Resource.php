<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceRating;

class Resource extends Model
{
    /** @use HasFactory<\Database\Factories\ResourceFactory> */
    use HasFactory;

    protected $guarded  = [];

    
    public function ratings() {
        return $this->hasMany(ResourceRating::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function getRatingAttribute() {
        return $this->ratings()->avg('rating');
    }

    protected $appends = ['rating'];
}
