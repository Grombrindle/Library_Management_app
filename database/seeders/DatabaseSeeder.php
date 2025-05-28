<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Admin;
use App\Models\Lecture;
use App\Models\Course;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $names = ['Maths', 'Science', 'Arabic', 'Physics', 'Chemistry', 'English', 'French', 'Philosophy', 'History', 'Arabic'];

        for ($i = 0; $i < 10; $i++) {
            $randomDigits = mt_rand(900000000, 999999999);

            User::factory()->create([
                'userName' => fake()->name(),
                'countryCode' => '+963',
                'number' => $randomDigits,
                'password' => Hash::make('password'),
                'isBanned' => 0,
            ]);
            $randomDigits = mt_rand(900000000, 999999999);
            $teacher = Teacher::factory()->create([
                'name' => fake()->name(),
                'userName' => fake()->name(),
                'countryCode' => '+963',
                'number' => $randomDigits,
                'image' => 'Images/Admins/teacherDefault.png',
                'links' => '{"Facebook": "https://facebook", "Instagram": "https://instagram", "Telegram": "https://telegram", "YouTube":"https://youtube"}',
                'password' => Hash::make('password'),
            ]);

            Admin::factory()->create([
                'name' => $teacher->name,
                'userName' => $teacher->userName,
                'password' => $teacher->password,
                'teacher_id' => $teacher->id,
                'countryCode' => '+963',
                'number' => $teacher->number,
                'privileges' => 0,
                'image' => $teacher->image,
            ]);
            $randomDigits = mt_rand(900000000, 999999999);
            Admin::factory()->create([
                'name' => fake()->name(),
                'userName' => fake()->name(),
                'password' => Hash::make('password'),
                'privileges' => rand(1, 2),
                'teacher_id' => null,
                'countryCode' => '+963',
                'image' => 'Images/Admins/adminDefault.png',
                'number' => $randomDigits,
            ]);
            $i < 7 ? $isSci = 1 : $isSci = 0;
            $subject = Subject::factory()->create([
                'name' => $names[$i],
                'lecturesCount' => 0,
                'subscriptions' => 0,
                'image' => 'Images/Subjects/default.png',
                'literaryOrScientific' => $isSci,
            ]);

            $teacher->subjects()->attach($subject->id);

            $course = Course::factory()->create([
                'name' => fake()->safeColorName(),
                'lecturesCount' => 0,
                'subscriptions' => 0,
                'description' => fake()->text(),
                'image' => 'Images/Courses/default.png',
                'teacher_id' => $teacher->id,
                'subject_id' => $subject->id,
            ]);
            $type = rand(0,1);
            $lecture = Lecture::factory()->create([
                'name' => fake()->name(),
                'type' => $type,
                'description' => fake()->text(),
                'image' => 'Images/Lectures/default.png',
                'course_id' => rand(1, Course::count()),
            ]);

            if ($type) {
                $lecture->file_360 = 'Files/360/default_360.mp4';
                $lecture->file_720 = 'Files/720/default_720.mp4';
                $lecture->file_1080 = 'Files/1080/default_1080.mp4';
            } else {
                $lecture->file_pdf = 'Files/PDFs/default_pdf.pdf';
            }
            $lecture->save();
            // $teacher->subjects()->attach($subject->id);
            $course->subscriptions = $course->users->count();
            $course->save();
        }
        // foreach (Lecture::all() as $lecture) {
        //     $subject = Subject::findOrFail($lecture->subject_id);
        //     $subject->lectures()->attach($lecture->id);
        //     $subject->lecturesCount = $subject->lectures()->count();
        //     $subject->save();
        // }

        $randomDigits = mt_rand(900000000, 999999999);
        Admin::factory()->create([
            'name' => 'admin',
            'userName' => 'admin',
            'password' => Hash::make('password'),
            'privileges' => 2,
            'teacher_id' => null,
            'countryCode' => '+963',
            'image' => 'Images/Admins/adminDefault.png',
            'number' => $randomDigits,
        ]);
        $randomDigits = mt_rand(900000000, 999999999);
        Admin::factory()->create([
            'name' => 'semiadmin',
            'userName' => 'semiadmin',
            'password' => Hash::make('password'),
            'privileges' => 1,
            'teacher_id' => null,
            'countryCode' => '+963',
            'image' => 'Images/Admins/adminDefault.png',
            'number' => $randomDigits,
        ]);
        $randomDigits = mt_rand(900000000, 999999999);
        $teacher = Teacher::factory()->create([
            'name' => 'teacher',
            'userName' => 'teacher',
            'countryCode' => '+963',
            'number' => $randomDigits,
            'image' => 'Images/Admins/teacherDefault.png',
            'links' => '{"Facebook": "https://facebook", "Instagram": "https://instagram", "Telegram": "https://telegram", "YouTube":"https://youtube"}',
            'password' => Hash::make('password'),
        ]);
        Admin::factory()->create([
            'name' => $teacher->name,
            'userName' => $teacher->userName,
            'password' => $teacher->password,
            'privileges' => 0,
            'teacher_id' => $teacher->id,
            'countryCode' => '+963',
            'image' => $teacher->image,
            'number' => $teacher->number,
        ]);
        
        User::factory()->create([
            'userName' => 'username',
            'countryCode' => '+963',
            'number' => 999999993,
            'password' => Hash::make('password'),
            'isBanned' => 0,
        ]);

        $this->call(SubjectSeeder::class);
        $this->call(TeacherSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(LectureSeeder::class);
        $this->call(SubscriptionSeeder::class);
        $this->call(QuizSeeder::class);
        $this->call(QuestionSeeder::class);
    }
}
