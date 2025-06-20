<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseRating;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $courses = Course::all();
        $ratings = [];
        $combinations = [];

        if ($users->isEmpty() || $courses->isEmpty()) {
            $this->command->info('No users or courses found, skipping course ratings seeding.');
            return;
        }

        for ($i = 0; $i < 100; $i++) {
            $user = $users->random();
            $course = $courses->random();
            $combination = $user->id . '-' . $course->id;

            if (!in_array($combination, $combinations)) {
                $ratings[] = [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'rating' => rand(1, 5),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $combinations[] = $combination;
            }
        }

        CourseRating::insert($ratings);
    }
}
