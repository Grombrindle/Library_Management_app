<?php



namespace Database\Factories;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    public function definition(): array
    {
        $answers = [
            'Option A',
            'Option B',
            'Option C',
            'Option D'
        ];

        return [
            'question_text' => $this->faker->sentence() . '?',
            'answers' => json_encode($answers),
            'correct_answer_index' => $this->faker->numberBetween(0, 3),
            'subject_id' => Subject::inRandomOrder()->first()->id ?? Subject::factory()->create()->id,
            'teacher_id' => Teacher::inRandomOrder()->first()->id ?? Teacher::factory()->create()->id,
        ];
    }
}
