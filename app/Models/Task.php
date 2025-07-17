<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'text',
        'isChecked',
        'isTrashed',
        'user_id',
        'deleted_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'trashed_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getDueDateAttribute()
    {
        if (!$this->updated_at || !$this->estimatedHours)
            return null;
        return $this->updated_at->copy()->addMinutes($this->estimatedHours * 60)->format('Y-m-d H:i:s');
    }

    protected $appends = ['dueDate'];
}
