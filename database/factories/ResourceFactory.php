<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subject;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resource>
 */
class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        
        // $table->string('name');
        // $table->text('description')->nullable();
        // $table->integer('literaryOrScientific');
        // $table->foreignIdFor(Subject::class);
        // $table->date('publish date');
        // $table->string('image')->default('/Images/Resources/default.png');
        // $table->string('file')->default('/Files/Resources/default.pdf');
        // $table->string('author')->default('John Doe');
        $name = fake()->name();
        $description = fake()->realText();
        $literaryOrScientific = rand(0, 1);
        $sub = rand(1, Subject::count());
        $pub = fake()->date();

        return [
            'name' => $name,
            'description' => $description,
            'literaryOrScientific' => $literaryOrScientific,
            'subject_id' => $sub,
            'publish date' => $pub,
        ];
    }
}
