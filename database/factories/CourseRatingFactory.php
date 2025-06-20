<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseRating;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseRating>
 */
class CourseRatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CourseRating::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get existing User and Course IDs
        $userIds = User::pluck('id')->toArray();
        $courseIds = Course::pluck('id')->toArray();

        return [
            'user_id' => $this->faker->randomElement($userIds),
            'course_id' => $this->faker->randomElement($courseIds),
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}
