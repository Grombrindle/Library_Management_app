<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceRating extends Model
{
    protected $table = 'resources_ratings';

    protected $fillable = [
        'user_id',
        'resource_id',
        'rating'
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
