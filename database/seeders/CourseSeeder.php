<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        for ($i = 0; $i < 10; $i++) {
            $teacher = Teacher::inRandomOrder()->first();
            $subject = $teacher->subjects()->inRandomOrder()->first();

            if (!$subject) {
                // If teacher has no subjects, skip this iteration
                continue;
            }

            $randomTime = fake()->date();

            $course = Course::factory()->create([
                'name' => fake()->safeColorName(),
                'lecturesCount' => 0,
                'subscriptions' => 0,
                'description' => fake()->text(),
                'image' => 'Images/Courses/default.png',
                'sources' => json_encode([
                    'Course Introduction' => fake()->url(),
                    'Basic Concepts' => fake()->url(),
                    'Advanced Topics' => fake()->url(),
                ]),
                'teacher_id' => $teacher->id,
                'subject_id' => $subject->id,
                'created_at' => $randomTime,
                'updated_at' => $randomTime,
            ]);

            // Add 2-4 random ratings for the course
            $numRatings = rand(2, 4);
            $users = User::inRandomOrder()->take($numRatings)->get();

            foreach ($users as $user) {
                $rating = rand(1, 5);
                DB::table('course_rating')->insert([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'rating' => $rating,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
