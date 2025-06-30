<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lecture;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class WatchlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $lectures = Lecture::all();
        $courses = Course::all();

        if ($lectures->isEmpty() && $courses->isEmpty()) {
            return; // No lectures or courses to add to watchlist
        }

        foreach ($users as $user) {
            // Random number of items (0-5) for each user
            $numItems = rand(0, 5);

            if ($numItems > 0) {
                for ($i = 0; $i < $numItems; $i++) {
                    $type = rand(0, 1); // 0 = lecture, 1 = course
                    if ($type === 0 && $lectures->count() > 0) {
                        $lecture = $lectures->random();
                        DB::table('watchlists')->insert([
                            'user_id' => $user->id,
                            'lecture_id' => $lecture->id,
                            'course_id' => null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    } elseif ($type === 1 && $courses->count() > 0) {
                        $course = $courses->random();
                        DB::table('watchlists')->insert([
                            'user_id' => $user->id,
                            'lecture_id' => null,
                            'course_id' => $course->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
        }
    }
}
