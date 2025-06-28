<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class score extends Model
{

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    protected $table = 'score';

    protected $fillable = [
        'user_id',
        'quiz_id',
        'correctAnswers',
        'created_at',
        'updated_at'
    ];
    //
}
