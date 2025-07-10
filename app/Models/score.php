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
        'sparks',
        'created_at',
        'updated_at'
    ];

    public function getAnswersAttribute() {
        $answers = json_decode($this->correctAnswers, true);
        if (!is_array($answers) || count($answers) === 0) {
            return 0;
        }
        $ones = array_sum($answers);
        $total = count($answers);
        return round(($ones / $total) * 100, 2) . "%";
    }

    public function getAnswersCountAttribute() {
        $answers = json_decode($this->correctAnswers, true);
        $ones = array_sum($answers);
        return $ones;
    }

    protected $appends = ['answers', 'answersCount'];
    //
}
