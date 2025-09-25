<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Admin;
use App\Models\Lecture;
use App\Models\Course;
use App\Models\Exam;
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

        $names = ['Maths', 'Science', 'Arabic', 'Physics', 'Chemistry', 'English', 'French', 'Religion', 'Philosophy', 'History', 'Arabic', 'French', 'English', 'Geography', 'Religion'];

        //     $randomDigits = mt_rand(900000000, 999999999);

        //     User::factory()->create([
        //         'userName' => fake()->name(),
        //         'countryCode' => '+963',
        //         'number' => $randomDigits,
        //         'password' => Hash::make('password'),
        //         'isBanned' => 0,
        //     ]);
        //     $randomDigits = mt_rand(900000000, 999999999);
        //     $teacher = Teacher::factory()->create([
        //         'name' => fake()->name(),
        //         'userName' => fake()->name(),
        //         'countryCode' => '+963',
        //         'number' => $randomDigits,
        //         'description' => fake()->text(),
        //         'image' => 'Images/Admins/teacherDefault.png',
        //         'links' => '{"Facebook": "https://facebook", "Instagram": "https://instagram", "Telegram": "https://telegram", "YouTube":"https://youtube"}',
        //         'password' => Hash::make('password'),
        //     ]);

        //     Admin::factory()->create([
        //         'name' => $teacher->name,
        //         'userName' => $teacher->userName,
        //         'password' => $teacher->password,
        //         'teacher_id' => $teacher->id,
        //         'countryCode' => '+963',
        //         'number' => $teacher->number,
        //         'privileges' => 0,
        //         'image' => $teacher->image,
        //     ]);
        //     $randomDigits = mt_rand(900000000, 999999999);
        //     Admin::factory()->create([
        //         'name' => fake()->name(),
        //         'userName' => fake()->name(),
        //         'password' => Hash::make('password'),
        //         'privileges' => rand(1, 2),
        //         'teacher_id' => null,
        //         'countryCode' => '+963',
        //         'image' => 'Images/Admins/adminDefault.png',
        //         'number' => $randomDigits,
        //     ]);
        //     $i <= 8 ? $isSci = 1 : $isSci = 0;
        //     $subject = Subject::factory()->create([
        //         'name' => $names[$i],
        //         'lecturesCount' => 0,
        //         'subscriptions' => 0,
        //         'image' => 'Images/Subjects/default.png',
        //         'literaryOrScientific' => $isSci,
        //     ]);

        //     $teacher->subjects()->attach($subject->id);

        //     $randomTime = fake()->date();

        //     $course = Course::factory()->create(attributes: [
        //         'name' => fake()->safeColorName(),
        //         'lecturesCount' => 0,
        //         'subscriptions' => 0,
        //         'description' => fake()->text(),
        //         'image' => 'Images/Courses/default.png',

        //         'sources' => json_encode([
        //             ['name' => 'Course Introduction', 'link' => fake()->url()],
        //             ['name' => 'Basic Concepts', 'link' => fake()->url()],
        //             ['name' => 'Advanced Topics', 'link' => fake()->url()],
        //         ]),
        //         'teacher_id' => $teacher->id,
        //         'subject_id' => $subject->id,
        //         'created_at' => $randomTime,
        //         'updated_at' => $randomTime,
        //     ]);

        //     // Add 2-4 random ratings for the course
        //     $numRatings = rand(2, 4);
        //     $users = User::inRandomOrder()->take($numRatings)->get();

        //     foreach ($users as $user) {
        //         $rating = rand(1, 5);
        //         DB::table('course_rating')->insert([
        //             'user_id' => $user->id,
        //             'course_id' => $course->id,
        //             'rating' => $rating,
        //             'review' => rand(0, 1) ? null : fake()->realText(100),
        //             'created_at' => now(),
        //             'updated_at' => now()
        //         ]);
        //     }

        //     $type = rand(0, 1);
        //     $lecture = Lecture::factory()->create([
        //         'name' => fake()->name(),
        //         'type' => $type,
        //         'description' => fake()->text(),
        //         'image' => 'Images/Lectures/default.png',
        //         'course_id' => rand(1, Course::count()),
        //         'duration' => $type ? rand(45, 3600) : null,
        //         'pages' => $type ? null : rand(1, 155),
        //     ]);

        //     // Add 2-4 random ratings for the course
        //     $numRatings = rand(2, 4);
        //     $users = User::inRandomOrder()->take($numRatings)->get();

        //     foreach ($users as $user) {
        //         $rating = rand(1, 5);
        //         DB::table('lecture_rating')->insert([
        //             'user_id' => $user->id,
        //             'lecture_id' => $lecture->id,
        //             'rating' => $rating,
        //             'review' => rand(0, 1) ? null : fake()->realText(100),
        //             'created_at' => now(),
        //             'updated_at' => now()
        //         ]);
        //         $rating = rand(1, 5);
        //         DB::table('teacher_ratings')->insert([
        //             'user_id' => $user->id,
        //             'teacher_id' => $teacher->id,
        //             'rating' => $rating,
        //             'review' => rand(0, 1) ? null : fake()->realText(100),
        //             'created_at' => now(),
        //             'updated_at' => now()
        //         ]);
        //     }
        //     if ($type) {
        //         $lecture->file_360 = 'Files/360/default_360.mp4';
        //         $lecture->file_720 = 'Files/720/default_720.mp4';
        //         $lecture->file_1080 = 'Files/1080/default_1080.mp4';
        //     } else {
        //         $lecture->file_pdf = 'Files/PDFs/default_pdf.pdf';
        //     }
        //     $lecture->save();
        //     // $teacher->subjects()->attach($subject->id);
        //     $course->subscriptions = $course->users->count();
        //     $course->save();
        // }
        // // foreach (Lecture::all() as $lecture) {
        // //     $subject = Subject::findOrFail($lecture->subject_id);
        // //     $subject->lectures()->attach($lecture->id);
        // //     $subject->lecturesCount = $subject->lectures()->count();
        // //     $subject->save();
        // // }

        // $randomDigits = mt_rand(900000000, 999999999);
        // Admin::factory()->create([
        //     'name' => 'admin',
        //     'userName' => 'admin',
        //     'password' => Hash::make('password'),
        //     'privileges' => 2,
        //     'teacher_id' => null,
        //     'countryCode' => '+963',
        //     'image' => 'Images/Admins/adminDefault.png',
        //     'number' => $randomDigits,
        // ]);
        // $randomDigits = mt_rand(900000000, 999999999);
        // Admin::factory()->create([
        //     'name' => 'semiadmin',
        //     'userName' => 'semiadmin',
        //     'password' => Hash::make('password'),
        //     'privileges' => 1,
        //     'teacher_id' => null,
        //     'countryCode' => '+963',
        //     'image' => 'Images/Admins/adminDefault.png',
        //     'number' => $randomDigits,
        // ]);
        // $randomDigits = mt_rand(900000000, 999999999);
        // $teacher = Teacher::factory()->create([
        //     'name' => 'teacher',
        //     'userName' => 'teacher',
        //     'countryCode' => '+963',
        //     'description' => fake()->text(),
        //     'number' => $randomDigits,
        //     'image' => 'Images/Admins/teacherDefault.png',
        //     'links' => '{"Facebook": "https://facebook", "Instagram": "https://instagram", "Telegram": "https://telegram", "YouTube":"https://youtube"}',
        //     'password' => Hash::make('password'),
        // ]);
        // // Assign a subject to this teacher
        // $subject = Subject::findOrFail($i);
        // $teacher->subjects()->attach($subject->id);
        // // Create a course for this teacher and subject
        // $course = Course::factory()->create([
        //     'name' => 'Special Course',
        //     'teacher_id' => $teacher->id,
        //     'subject_id' => $subject->id,
        //     'lecturesCount' => 0,
        //     'subscriptions' => 0,
        //     'description' => 'A special course for the seeded teacher.',
        //     'image' => 'Images/Courses/default.png',
        //     'sources' => json_encode([
        //         ['name' => 'Course Introduction', 'link' => 'https://example.com/intro'],
        //     ]),
        // ]);
        // // Create a lecture for this course
        // $lecture = Lecture::factory()->create([
        //     'name' => 'Special Lecture',
        //     'course_id' => $course->id,
        //     'type' => 1,
        //     'description' => 'A special lecture for the seeded course.',
        //     'image' => 'Images/Lectures/default.png',
        //     'file_360' => 'Files/360/default_360.mp4',
        //     'file_720' => 'Files/720/default_720.mp4',
        //     'file_1080' => 'Files/1080/default_1080.mp4',
        //     'duration' => $type ? rand(45, 3600) : null,
        //     'pages' => $type ? null : rand(1, 155),
        // ]);
        // Admin::factory()->create([
        //     'name' => $teacher->name,
        //     'userName' => $teacher->userName,
        //     'password' => $teacher->password,
        //     'privileges' => 0,
        //     'teacher_id' => $teacher->id,
        //     'countryCode' => '+963',
        //     'image' => $teacher->image,
        //     'number' => $teacher->number,
        // ]);

        // User::factory()->create([
        //     'userName' => 'username',
        //     'countryCode' => '+963',
        //     'number' => 999999993,
        //     'password' => Hash::make('password'),
        //     'isBanned' => 0,
        //     'sparkies' => 5,
        // ]);

        // $this->call(SubjectSeeder::class);
        // $this->call(TeacherSeeder::class);
        // $this->call(CourseSeeder::class);
        // $this->call(LectureSeeder::class);
        // $this->call(SubscriptionSeeder::class);
        // $this->call(QuizSeeder::class);
        // $this->call(QuestionSeeder::class);
        // $this->call(ResourceSeeder::class);
        // $this->call(TaskSeeder::class);
        // $this->call(WatchlistSeeder::class);
        // $this->call(HelpfulSeeder::class);
        // $this->call(SavedMessageSeeder::class);

        foreach ($names as $i => $name) {
            $subject = Subject::factory()->create([
                'name' => $name,
                'image' => 'Images/Subjects/default.png',
                'literaryOrScientific' => $i > 7 ? 0 : 1,
            ]);
        }

        $subjects = Subject::all(); // Preset subjects

        for ($i = 0; $i < 20; $i++) {

            $randomDigits = mt_rand(900000000, 999999999);

            User::factory()->create([
                'userName' => fake()->name(),
                'countryCode' => '+963',
                'number' => $randomDigits,
                'password' => Hash::make('password'),
                'isBanned' => 0,
            ]);
        }


        for ($t = 0; $t < 20; $t++) {
            // Create Teacher
            $teacher = Teacher::create([
                'name' => fake()->name(),
                'userName' => fake()->userName(),
                'countryCode' => '+963',
                'number' => mt_rand(900000000, 999999999),
                'description' => fake()->text(),
                'image' => 'Images/Admins/teacherDefault.png',
                'links' => json_encode([
                    'Facebook' => 'https://facebook.com',
                    'Instagram' => 'https://instagram.com',
                    'Telegram' => 'https://telegram.org',
                    'YouTube' => 'https://youtube.com',
                ]),
                'password' => Hash::make('password'),
            ]);


            // Assign 1–3 random subjects to the teacher
            $assignedSubjects = $subjects->random(rand(1, 3));
            $teacher->subjects()->attach($assignedSubjects->pluck('id'));

            foreach ($assignedSubjects as $subject) {
                $pur = rand(0, 1);
                $price = rand(1, 20);
                // Create course for teacher and subject
                $course = Course::create([
                    'name' => fake()->safeColorName(),
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                    'description' => fake()->text(),
                    'image' => 'Images/Courses/default.png',
                    'lecturesCount' => 0,
                    'subscriptions' => 0,
                    'price' => $price,
                    'sparkies' => $pur,
                    'sparkiesPrice' => $pur ? min(round($price / 5), 3) : 0,
                    'sources' => json_encode([
                        ['name' => 'Course Introduction', 'link' => fake()->url()],
                        ['name' => 'Basic Concepts', 'link' => fake()->url()],
                        ['name' => 'Advanced Topics', 'link' => fake()->url()],
                    ]),
                ]);

                // Add 2-4 random ratings for the course
                $numRatings = rand(2, 4);
                $users = User::inRandomOrder()->take($numRatings)->get();

                foreach ($users as $user) {
                    $rating = rand(1, 5);
                    DB::table('course_rating')->insert([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'rating' => $rating,
                        'review' => rand(0,3) ? fake()->text(250) : null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                // Create 3–4 lectures for the course
                $lectureCount = rand(3, 4);
                for ($i = 0; $i < $lectureCount; $i++) {
                    $type = rand(0, 1); // 0 = PDF, 1 = Video

                    $lecture = Lecture::create([
                        'name' => fake()->word(),
                        'type' => $type,
                        'description' => fake()->text(),
                        'image' => 'Images/Lectures/default.png',
                        'course_id' => $course->id,
                        'duration' => $type ? rand(60, 3600) : null,
                        'pages' => $type ? null : rand(1, 150),
                        'file_pdf' => $type ? null : 'Files/PDFs/default_pdf.pdf',
                        'file_360' => $type ? 'Files/360/default_360.mp4' : null,
                        'file_720' => $type ? 'Files/720/default_720.mp4' : null,
                        'file_1080' => $type ? 'Files/1080/default_1080.mp4' : null,
                    ]);

                    $numRatings = rand(1, 2);
                    $users = User::inRandomOrder()->take($numRatings)->get();

                    foreach ($users as $user) {
                        $rating = rand(1, 5);
                        DB::table('lecture_rating')->insert([
                            'user_id' => $user->id,
                            'lecture_id' => $lecture->id,
                            'rating' => $rating,
                            'review' => rand(0,3) ? fake()->text(150) : null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }

                // Update course lecture count
                $course->lecturesCount = $course->lectures()->count();
                $course->save();
            }

            // Admin linked to teacher
            Admin::create([
                'name' => $teacher->name,
                'userName' => $teacher->userName,
                'password' => $teacher->password,
                'privileges' => 0,
                'teacher_id' => $teacher->id,
                'countryCode' => $teacher->countryCode,
                'number' => $teacher->number,
                'image' => $teacher->image,
            ]);

            $exam = Exam::create([
                'title' => fake()->name(),
                'description' => fake()->text(120),
                'date' => fake()->date(),
                'subject_id' => rand(1, Subject::count()),
                'pages' => 87
            ]);
        }
        Admin::create([
            'name' => 'admin',
            'userName' => 'admin',
            'password' => Hash::make('password'),
            'privileges' => 2,
            'teacher_id' => null,
            'countryCode' => '+963',
            'number' => mt_rand(900000000, 999999999),
            'image' => 'Images/Admins/adminDefault.png',
        ]);

        Admin::create([
            'name' => 'semiadmin',
            'userName' => 'semiadmin',
            'password' => Hash::make('password'),
            'privileges' => 1,
            'teacher_id' => null,
            'countryCode' => '+963',
            'number' => mt_rand(900000000, 999999999),
            'image' => 'Images/Admins/adminDefault.png',
        ]);

        // Add 1 special user
        $user = User::create([
            'userName' => 'username',
            'countryCode' => '+963',
            'number' => 999999993,
            'password' => Hash::make('password'),
            'isBanned' => 0,
            'sparkies' => 5,
        ]);

        $randomDigits = mt_rand(900000000, 999999999);
        $teacher = Teacher::factory()->create([
            'name' => 'teacher',
            'userName' => 'teacher',
            'countryCode' => '+963',
            'description' => fake()->text(),
            'number' => $randomDigits,
            'image' => 'Images/Admins/teacherDefault.png',
            'links' => '{"Facebook": "https://facebook", "Instagram": "https://instagram", "Telegram": "https://telegram", "YouTube":"https://youtube"}',
            'password' => Hash::make('password'),
        ]);
        // Assign a subject to this teacher
        $assignedSubjects = $subjects->random(rand(1, 3));
        $teacher->subjects()->attach($assignedSubjects->pluck('id'));
        foreach ($assignedSubjects as $subject) {
            $pur = rand(0, 1);
            $price = rand(1, 20);
            // Create course for teacher and subject
            $course = Course::create([
                'name' => fake()->safeColorName(),
                'teacher_id' => $teacher->id,
                'subject_id' => $subject->id,
                'description' => fake()->text(),
                'image' => 'Images/Courses/default.png',
                'lecturesCount' => 0,
                'subscriptions' => 0,
                'price' => $price,
                'sparkies' => $pur,
                'sparkiesPrice' => $pur ? min(round($price / 5), 3) : 0,
                'sources' => json_encode([
                    ['name' => 'Course Introduction', 'link' => fake()->url()],
                    ['name' => 'Basic Concepts', 'link' => fake()->url()],
                    ['name' => 'Advanced Topics', 'link' => fake()->url()],
                ]),
            ]);



            // Add 2-4 random ratings for the course
            $numRatings = rand(2, 4);
            $users = User::inRandomOrder()->take($numRatings)->get();

            foreach ($users as $user) {
                $rating = rand(1, 5);
                DB::table('course_rating')->insert([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'rating' => $rating,
                    'review' => rand(0,3) ? fake()->text(250) : null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Create 3–4 lectures for the course
            $lectureCount = rand(3, 4);
            for ($i = 0; $i < $lectureCount; $i++) {
                $type = rand(0, 1); // 0 = PDF, 1 = Video

                $lecture = Lecture::create([
                    'name' => fake()->word(),
                    'type' => $type,
                    'description' => fake()->text(),
                    'image' => 'Images/Lectures/default.png',
                    'course_id' => $course->id,
                    'duration' => $type ? rand(60, 3600) : null,
                    'pages' => $type ? null : rand(1, 150),
                    'file_pdf' => $type ? null : 'Files/PDFs/default_pdf.pdf',
                    'file_360' => $type ? 'Files/360/default_360.mp4' : null,
                    'file_720' => $type ? 'Files/720/default_720.mp4' : null,
                    'file_1080' => $type ? 'Files/1080/default_1080.mp4' : null,
                ]);

                $numRatings = rand(1, 2);
                $users = User::inRandomOrder()->take($numRatings)->get();

                foreach ($users as $user) {
                    $rating = rand(1, 5);
                    DB::table('lecture_rating')->insert([
                        'user_id' => $user->id,
                        'lecture_id' => $lecture->id,
                        'rating' => $rating,
                        'review' => rand(0,3) ? fake()->text(250) : null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            // Update course lecture count
            $course->lecturesCount = $course->lectures()->count();
            $course->save();
        }

        // Admin linked to teacher
        Admin::create([
            'name' => $teacher->name,
            'userName' => $teacher->userName,
            'password' => $teacher->password,
            'privileges' => 0,
            'teacher_id' => $teacher->id,
            'countryCode' => $teacher->countryCode,
            'number' => $teacher->number,
            'image' => $teacher->image,
        ]);
        foreach (Teacher::all() as $teacher) {

            $numRatings = rand(2, 4);
            $users = User::inRandomOrder()->take($numRatings)->get();

            foreach ($users as $user) {
                $rating = rand(1, 5);
                DB::table('teacher_ratings')->insert([
                    'user_id' => $user->id,
                    'teacher_id' => $teacher->id,
                    'rating' => $rating,
                    'review' => fake()->text(150),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        $this->call(SubscriptionSeeder::class);
        $this->call(ResourceSeeder::class);
        $this->call(HelpfulSeeder::class);
        $this->call(QuizSeeder::class);
        $this->call(QuestionSeeder::class);
    }
}

