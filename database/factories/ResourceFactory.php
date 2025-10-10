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
        $name = fake()->lastName();
        $description = fake()->realText(200);
        $literaryOrScientific = rand(0, 1);
        $subject = Subject::inRandomOrder()->first() ?? Subject::factory()->create();
        $publishDate = fake()->date();
        $audio_file = rand(0, 1) == 0 ? null : 'Files/Resources/Audio/default.mp3';
        $author = fake()->name();

        return [
            'name' => $name,
            'description' => $description,
            'literaryOrScientific' => $literaryOrScientific,
            'subject_id' => $subject->id,
            'publish date' => $publishDate,
            'image' => 'Images/Resources/default.png',
            'audio_file' => $audio_file,
            'pdf_files' => json_encode([
                'ar' => 'Files/Resources/default.pdf',
                'en' => 'Files/Resources/default.pdf',
                'fr' => 'Files/Resources/default.pdf',
                'es' => 'Files/Resources/default.pdf',
                'de' => 'Files/Resources/default.pdf',
            ]),
            'author' => $author,
            'pages' => 95, // Will be set when actual PDF files are uploaded
        ];
    }
}
