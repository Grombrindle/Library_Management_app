<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'action_type',
        'target_type',
        'target_id',
        'payload',
        'status',
        'admin_response',
        'admin_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Get the teacher that owns the request
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the admin who processed this request
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
} 