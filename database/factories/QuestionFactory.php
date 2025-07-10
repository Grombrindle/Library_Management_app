<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Quiz;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $num = rand(2, 4);

        $diff = rand(1, 3);

        $answers = [];

        for ($i = 0; $i < $num; $i++) {
            if ($num == 2) {
                $answers[] = ($i == 0) ? "True" : "False";
            } else {
                $answers[] = $this->faker->sentence(3, true);
            }
        }

        if ($diff == 1)
            $diff = 'EASY';
        else if ($diff == 3)
            $diff = 'HARD';
        else
            $diff = 'MEDIUM';


        return [
            'questionText' => $this->faker->sentence() . '?',
            'options' => json_encode($answers, JSON_UNESCAPED_SLASHES),
            'correctAnswerIndex' => $this->faker->numberBetween(0, $num - 1),
            'difficulty' => $diff,
            'quiz_id' => Quiz::inRandomOrder()->first()->id ?? Quiz::factory()->create()->id,
        ];
    }
}
