<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceRating extends Model
{

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    protected $table = 'resources_ratings';

    protected $fillable = [
        'user_id',
        'resource_id',
        'rating',
        'review'
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
