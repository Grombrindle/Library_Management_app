<?php

namespace App\Actions\Ratings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpsertRatingAction
{
    /**
     * @param string $table table name (e.g., 'lecture_rating', 'course_rating', 'resources_ratings', 'teacher_ratings')
     * @param array $ids e.g., ['lecture_id' => 1] or ['course_id' => 5]
     * @param array $data e.g., ['rating' => 4, 'review' => '...']
     */
    public function __invoke(string $table, array $ids, array $data): bool
    {
        $keys = array_merge(['user_id' => Auth::id()], $ids);
        $values = array_merge($data, ['updated_at' => now()]);

        return DB::table($table)->updateOrInsert($keys, $values) ? true : false;
    }
}


