<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Subject;

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

            $course = Course::factory()->create([
                'name' => fake()->safeColorName(),
                'lecturesCount' => 0,
                'subscriptions' => 0,
                'description' => fake()->text(),
                'image' => 'Images/Courses/default.png',
                'teacher_id' => $teacher->id,
                'subject_id' => $subject->id,
            ]);
        }
    }
}
