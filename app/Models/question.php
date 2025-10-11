<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;

    protected $fillable = [
        'questionText',
        'options',
        'correctAnswerIndex',
        'quiz_id',
        'difficulty',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
        'options' => 'array'
    ];
    public function quiz() {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

}
