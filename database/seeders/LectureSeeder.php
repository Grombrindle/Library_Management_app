<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lecture;
use App\Models\Subject;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 0; $i < 10; $i++) {
            // $lectureTypes = ['MP4', 'PDF'];
            $randSub = rand(1,Course::count());
            $type = rand(0,1);

            $lecture = Lecture::factory()->create([
                'name' => fake()->name(),
                'description' => fake()->text(),
                // 'type' => $lectureTypes[array_rand($lectureTypes)],
                'type' => $type,
                'course_id' => $randSub,
                'image' => 'Images/Lectures/default.png',
            ]);
            if($type) {

                $lecture->file_360 = 'Files/360/default_360.mp4';
                $lecture->file_720 ='Files/720/default_720.mp4';
                $lecture->file_1080 = 'Files/1080/default_1080.mp4';
            }
            else {
                $lecture->file_pdf = 'Files/PDFs/default_pdf.pdf';
            }
            $lecture->save();
            
            $numRatings = rand(2, 4);
            $users = User::inRandomOrder()->take($numRatings)->get();
            
            foreach ($users as $user) {
                $rating = min([rand(1, 5) + (rand(0, 1) * 0.5), 5]); // This will give us whole numbers or half numbers
                DB::table('lecture_rating')->insert([
                    'user_id' => $user->id,
                    'lecture_id' => $lecture->id,
                    'rating' => $rating,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            $course = Course::findOrFail($randSub);
            // $course->lecturesCount++;
            // $course->save();
            // $course->lectures()->attach($lecture->id);

            $course->lecturesCount = Course::withCount('lectures')->find($course->id)->lectures_count;
            $course->save();
        }
        //
    }
}
