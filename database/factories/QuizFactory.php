<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Lecture;
use App\Models\Teacher;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lecture_id' => function() {
                do {
                    echo(Quiz::count());
                    $lectureId = Lecture::inRandomOrder()->first()->id ?? Lecture::factory()->create()->id;
                } while (Quiz::where('lecture_id', $lectureId)->exists());
                
                return $lectureId;
            },
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($quiz) {
            $lecture = Lecture::find($quiz->lecture_id);
            if ($lecture) {
                $lecture->quiz_id = $quiz->id;
                $lecture->save();
            }
        });
    }
}
