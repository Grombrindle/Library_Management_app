<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            // Scientific
            ['name' => 'Mathematics', 'literaryOrScientific' => true, 'image' => 'https://via.placeholder.com/150/4a86e8/ffffff?text=Math'],
            ['name' => 'Physics', 'literaryOrScientific' => true, 'image' => 'https://via.placeholder.com/150/4a86e8/ffffff?text=Physics'],
            ['name' => 'Chemistry', 'literaryOrScientific' => true, 'image' => 'https://via.placeholder.com/150/4a86e8/ffffff?text=Chemistry'],
            ['name' => 'Biology', 'literaryOrScientific' => true, 'image' => 'https://via.placeholder.com/150/4a86e8/ffffff?text=Biology'],
            ['name' => 'Computer Science', 'literaryOrScientific' => true, 'image' => 'https://via.placeholder.com/150/4a86e8/ffffff?text=CS'],

            // Literary
            ['name' => 'History', 'literaryOrScientific' => false, 'image' => 'https://via.placeholder.com/150/e86a4a/ffffff?text=History'],
            ['name' => 'Literature', 'literaryOrScientific' => false, 'image' => 'https://via.placeholder.com/150/e86a4a/ffffff?text=Literature'],
            ['name' => 'Philosophy', 'literaryOrScientific' => false, 'image' => 'https://via.placeholder.com/150/e86a4a/ffffff?text=Philosophy'],
            ['name' => 'Art History', 'literaryOrScientific' => false, 'image' => 'https://via.placeholder.com/150/e86a4a/ffffff?text=Art'],
            ['name' => 'Languages', 'literaryOrScientific' => false, 'image' => 'https://via.placeholder.com/150/e86a4a/ffffff?text=Languages'],
        ];

        foreach ($subjects as $subject) {
            Subject::create([
                'name' => $subject['name'],
                'literaryOrScientific' => $subject['literaryOrScientific'],
                'image' => $subject['image'],
                'lecturesCount' => 0,
                'subscriptions' => 0,
            ]);
        }
    }
}
