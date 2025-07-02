<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $subject_id
 * @property int $teacher_id
 * @property string $question_text
 * @property string $answers
 * @property int $correct_answer_index
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Subject $subject
 * @property-read \App\Models\Teacher $teacher
 * @method static \Database\Factories\QuizFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereAnswers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereCorrectAnswerIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereQuestionText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Quiz extends Model
{

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    use HasFactory;

    protected $fillable = [
        'lecture_id',
        'created_at',
        'updated_at'
    ];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class,'lecture_id');
    }
    public function questions() {
        return $this->hasMany(Question::class, 'quiz_id');
    }
}
