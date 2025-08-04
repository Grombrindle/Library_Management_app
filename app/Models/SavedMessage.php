<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedMessage extends Model
{
    /** @use HasFactory<\Database\Factories\SavedMessageFactory> */
    use HasFactory;

    protected $fillable = [
        'text',
        'user_id',
        'date',
        'created_at',
        'updated_at'
    ];
}
