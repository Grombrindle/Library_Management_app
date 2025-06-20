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
        $subjects = Subject::with('teachers')->get();

        if ($subjects->isEmpty()) {
            $this->command->info('Please seed subjects and their teachers first.');
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            $subject = $subjects->random();
            
            if ($subject->teachers->isNotEmpty()) {
                $teacher = $subject->teachers->random();
                
                $randomTime = fake()->date();

                $course = Course::factory()->create([
                    'name' => fake()->safeColorName(),
                    'lecturesCount' => 0,
                    'subscriptions' => 0,
                    'description' => fake()->text(),
                    'image' => 'https://picsum.photos/seed/' . fake()->unique()->word . '/800/600',
                    'sources' => json_encode([
                        ['name' => 'Course Introduction', 'link' => fake()->url()],
                        ['name' => 'Basic Concepts', 'link' => fake()->url()],
                        ['name' => 'Advanced Topics', 'link' => fake()->url()]
                    ]),
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                    'created_at' => $randomTime,
                    'updated_at' => $randomTime,
                ]);
            }
        }
    }
}
