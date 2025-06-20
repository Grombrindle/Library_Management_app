<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'userName' => $this->faker->unique()->userName(),
            'countryCode' => $this->faker->randomElement(['+1', '+44', '+91', '+61']),
            'number' => $this->faker->unique()->numerify('##########'),
            'password' => Hash::make('password'),
            'image' => $this->faker->imageUrl(400, 400, 'people'),
        ];
    }
}
