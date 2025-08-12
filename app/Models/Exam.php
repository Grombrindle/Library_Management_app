<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    /** @use HasFactory<\Database\Factories\ExamFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'thumbnailUrl',
        'pdf',
        'subject_id',
        'pages',
        'date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'pages' => 'integer',
    ];

    /**
     * Get the subject that owns the exam.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function getpdfUrlAttribute() {
        return Url($this->pdf);
    }

    public function getSubjectNameAttribute() {
        return Subject::findOrFail($this->subject_id)->name;
    }

    public function getLiteraryOrScientificAttribute() {
        return Subject::findOrFail($this->subject_id)->literaryOrScientific ? "Scientific" : "Literary";
    }


    protected $appends = ['pdfUrl', 'subjectName', 'literaryOrScientific'];
}
