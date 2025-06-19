<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Admin;
use App\Models\Lecture;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

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

            $randomTime = fake()->date();

            $course = Course::factory()->create(attributes: [
                'name' => fake()->safeColorName(),
                'lecturesCount' => 0,
                'subscriptions' => 0,
                'description' => fake()->text(),
                'image' => 'Images/Courses/default.png',

                'sources' => json_encode([
                    ['name' => 'Course Introduction', 'link' => fake()->url()],
                    ['name' => 'Basic Concepts', 'link' => fake()->url()],
                    ['name' => 'Advanced Topics', 'link' => fake()->url()]
                ]),
                'teacher_id' => $teacher->id,
                'subject_id' => $subject->id,
                'created_at' => $randomTime,
                'updated_at' => $randomTime,
            ]);

            // Add 2-4 random ratings for the course
            $numRatings = rand(2, 4);
            $users = User::inRandomOrder()->take($numRatings)->get();

            foreach ($users as $user) {
                $rating = min([rand(1, 5) + (rand(0, 1) * 0.5), 5]); // This will give us whole numbers or half numbers
                DB::table('course_rating')->insert([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'rating' => $rating,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            $type = rand(0, 1);
            $lecture = Lecture::factory()->create([
                'name' => fake()->name(),
                'type' => $type,
                'description' => fake()->text(),
                'image' => 'Images/Lectures/default.png',
                'course_id' => rand(1, Course::count()),
            ]);

            // Add 2-4 random ratings for the course
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
        // Assign a subject to this teacher
        $subject = Subject::factory()->create([
            'name' => 'Special Subject',
            'lecturesCount' => 0,
            'subscriptions' => 0,
            'image' => 'Images/Subjects/default.png',
            'literaryOrScientific' => 1,
        ]);
        $teacher->subjects()->attach($subject->id);
        // Create a course for this teacher and subject
        $course = Course::factory()->create([
            'name' => 'Special Course',
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'lecturesCount' => 0,
            'subscriptions' => 0,
            'description' => 'A special course for the seeded teacher.',
            'image' => 'Images/Courses/default.png',
            'sources' => json_encode([
                ['name' => 'Course Introduction', 'link' => 'https://example.com/intro'],
            ]),
        ]);
        // Create a lecture for this course
        $lecture = Lecture::factory()->create([
            'name' => 'Special Lecture',
            'course_id' => $course->id,
            'type' => 1,
            'description' => 'A special lecture for the seeded course.',
            'image' => 'Images/Lectures/default.png',
            'file_360' => 'Files/360/default_360.mp4',
            'file_720' => 'Files/720/default_720.mp4',
            'file_1080' => 'Files/1080/default_1080.mp4',
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
        $this->call(ResourceSeeder::class);
    }
}
