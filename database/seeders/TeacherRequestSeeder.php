<?php

namespace Database\Seeders;

use App\Models\TeacherRequest;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Only create seeds if we have teachers
        $teachers = Teacher::all();
        
        if ($teachers->count() == 0) {
            return;
        }
        
        // Sample course add request
        TeacherRequest::create([
            'teacher_id' => $teachers->random()->id,
            'action_type' => 'add',
            'target_type' => 'course',
            'payload' => [
                'name' => 'New Advanced Mathematics Course',
                'description' => 'A comprehensive advanced mathematics course covering calculus, linear algebra, and differential equations.',
                'teacher_id' => $teachers->random()->id,
                'sources' => json_encode([
                    'YouTube' => 'https://youtube.com/playlist?123',
                    'Notes' => 'https://example.com/notes'
                ])
            ],
            'status' => 'pending',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);
        
        // Sample lecture edit request
        TeacherRequest::create([
            'teacher_id' => $teachers->random()->id,
            'action_type' => 'edit',
            'target_type' => 'lecture',
            'target_id' => 1, // Assuming there's a lecture with ID 1
            'payload' => [
                'title' => 'Updated Lecture Title',
                'description' => 'Updated lecture description with more detailed information.',
                'videos_360p' => 'https://example.com/videos/lecture1_360p.mp4',
                'videos_720p' => 'https://example.com/videos/lecture1_720p.mp4',
                'videos_1080p' => 'https://example.com/videos/lecture1_1080p.mp4',
            ],
            'status' => 'pending',
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);
        
        // Sample resource delete request
        TeacherRequest::create([
            'teacher_id' => $teachers->random()->id,
            'action_type' => 'delete',
            'target_type' => 'resource',
            'target_id' => 1, // Assuming there's a resource with ID 1
            'payload' => [
                'title' => 'Outdated Resource Material'
            ],
            'status' => 'pending',
            'created_at' => now()->subHours(12),
            'updated_at' => now()->subHours(12),
        ]);
    }
} 