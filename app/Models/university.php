<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachers
 * @property-read int|null $teachers_count
 * @method static \Database\Factories\universityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|university newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|university newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|university query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|university whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|university whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|university whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|university whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|university whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class university extends Model
{
    /** @use HasFactory<\Database\Factories\UniversityFactory> */
    use HasFactory;

    protected $guarded = [];

    public function teachers() {
        return $this->belongsToMany(Teacher::class, 'teacher_university');
    }
}
