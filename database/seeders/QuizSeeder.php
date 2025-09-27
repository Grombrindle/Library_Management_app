<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Lecture;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // Get all lectures that don't have a quiz yet
        $lectures = Lecture::whereDoesntHave('quiz')->get();

        // Create a quiz for each lecture
        foreach ($lectures as $lecture) {
                $quiz = Quiz::create([
                    'lecture_id' => $lecture->id
                ]);
                // Update the lecture's quiz_id
                $lecture->quiz_id = $quiz->id;
                $lecture->save();
        }
    }
}
