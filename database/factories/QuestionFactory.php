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
        $num = rand(2,4);
        
        $answers = [];

        for($i = 0; $i < $num; $i++) {
            $num == 2 ? ($i == 0 ? $answers[] = "True" : $answers[] = "False") : $answers[] = $this->faker->sentence(3, true);
        }
        

        return [
            'questionText' => $this->faker->sentence() . '?',
            'options' => json_encode($answers),
            'correctAnswerIndex' => $this->faker->numberBetween(0, 3),
            'quiz_id' => Quiz::inRandomOrder()->first()->id ?? Quiz::factory()->create()->id,
        ];
    }
}
