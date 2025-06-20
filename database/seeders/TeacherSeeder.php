<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\University;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = Subject::all();
        $universities = University::all();

        if ($subjects->isEmpty() || $universities->isEmpty()) {
            $this->command->info('Please seed subjects and universities first.');
            return;
        }

        $teachers = Teacher::factory(50)->create([
            'image' => function() {
                // Generates a random image URL from Picsum Photos
                return 'https://picsum.photos/seed/' . fake()->unique()->word . '/400/400';
            }
        ]);

        // Assign subjects and universities to each teacher
        foreach ($teachers as $teacher) {
            // Assign 1 to 3 random subjects
            $teacher->subjects()->attach(
                $subjects->random(rand(1, 3))->pluck('id')->toArray()
            );

            // Assign 1 to 2 random universities
            $teacher->universities()->attach(
                $universities->random(rand(1, 2))->pluck('id')->toArray()
            );
        }
    }
}
