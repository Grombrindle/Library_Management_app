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

        foreach ($names as $i => $name) {
            $subject = Subject::factory()->create([
                'name' => $name,
                'image' => 'Images/Subjects/' . rand(1, 4) . ".png",
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
                'image' => 'Images/Admins/' . rand(1, 4) . ".png",
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

            $exam = Exam::create([
                'title' => fake()->name(),
                'description' => fake()->text(120),
                'date' => fake()->date(),
                'subject_id' => rand(1, Subject::count()),
                'pages' => 87,
                'thumbnailUrl' => 'Images/Exams/' . rand(1, 4) . ".png",
            ]);
        }

        $numFull = 1;
        $numEmpty = 1;

        foreach (Teacher::all() as $teacher) {
            if (rand(0, 1)) {
                $teacher->name = "Full Teacher " . $numFull;
                $teacher->save();

                $numFull++;

                $pur = rand(0, 1);
                $price = rand(1, 20);

                foreach ($teacher->subjects as $subject) {
                    $course = Course::create([
                        'name' => fake()->safeColorName(),
                        'teacher_id' => $teacher->id,
                        'subject_id' => $subject->id,
                        'description' => fake()->text(),
                        'image' => 'Images/Courses/' . rand(1, 4) . ".png",
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
                }
            } else {
                $teacher->name = "Empty Teacher " . $numEmpty;
                $numEmpty++;
                $teacher->save();
            }


            // Admin linked to teacher
            $admin = Admin::create([
                'name' => $teacher->name,
                'userName' => $teacher->userName,
                'password' => $teacher->password,
                'privileges' => 0,
                'teacher_id' => $teacher->id,
                'countryCode' => $teacher->countryCode,
                'number' => $teacher->number,
                'image' => $teacher->image,
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
                'image' => 'Images/Courses/' . rand(1, 4) . ".png",
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

        $numFull = 1;
        $numEmpty = 1;

        foreach (Course::all() as $course) {
            if (rand(0, 1)) {
                $course->name = "Full Course ".$numFull;
                $course->save();

                $numFull++;

                // Create 3–4 lectures for the course
                $lectureCount = rand(3, 4);
                for ($i = 0; $i < $lectureCount; $i++) {
                    $type = rand(0, 1); // 0 = PDF, 1 = Video

                    $lecture = Lecture::create([
                        'name' => fake()->word(),
                        'type' => $type,
                        'description' => fake()->text(),
                        'image' => 'Images/Lectures/' . rand(1, 4) . ".png",
                        'course_id' => $course->id,
                        'duration' => $type ? rand(60, 3600) : null,
                        'pages' => $type ? null : rand(1, 150),
                        'file_pdf' => $type ? null : 'Files/PDFs/default_pdf.pdf',
                        'file_360' => $type ? 'Files/360/default_360.mp4' : null,
                        'file_720' => $type ? 'Files/720/default_720.mp4' : null,
                        'file_1080' => $type ? 'Files/1080/default_1080.mp4' : null,
                    ]);

                }

                // Update course lecture count
                $course->lecturesCount = $course->lectures()->count();
                $course->save();

            }
            else {
                $course->name ="Empty Course ".$numEmpty;
                $course->save();

                $numEmpty++;
            }
        }


        foreach (Teacher::all() as $teacher) {
            if (rand(0, 1)) {
                $teacher->name = $teacher->name . " With Rev";
                $teacher->save();

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
            } else {
                $teacher->name = $teacher->name . " No Rev";
                $teacher->save();
            }
        }
        foreach (Course::all() as $course) {
            if (rand(0, 1)) {
                $course->name = $course->name . " With Rev";
                $course->save();


                // Add 2-4 random ratings for the course
                $numRatings = rand(2, 4);
                $users = User::inRandomOrder()->take($numRatings)->get();

                foreach ($users as $user) {
                    $rating = rand(1, 5);
                    DB::table('course_rating')->insert([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'rating' => $rating,
                        'review' => rand(0, 3) ? fake()->text(250) : null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            } else {
                $course->name = $course->name . " No Rev";
                $course->save();
            }
        }
        foreach (Lecture::all() as $lecture) {
            if (rand(0, 1)) {
                $lecture->name = $lecture->name . " With Rev";
                $lecture->save();

                $numRatings = rand(1, 4);
                $users = User::inRandomOrder()->take($numRatings)->get();

                foreach ($users as $user) {
                    $rating = rand(1, 5);
                    DB::table('lecture_rating')->insert([
                        'user_id' => $user->id,
                        'lecture_id' => $lecture->id,
                        'rating' => $rating,
                        'review' => rand(0, 3) ? fake()->text(250) : null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            } else {
                $lecture->name = $lecture->name . " No Rev";
                $lecture->save();
            }
        }
        // foreach (Teacher::all() as $teacher) {

        //     $numRatings = rand(2, 4);
        //     $users = User::inRandomOrder()->take($numRatings)->get();

        //     foreach ($users as $user) {
        //         $rating = rand(1, 5);
        //         DB::table('teacher_ratings')->insert([
        //             'user_id' => $user->id,
        //             'teacher_id' => $teacher->id,
        //             'rating' => $rating,
        //             'review' => fake()->text(150),
        //             'created_at' => now(),
        //             'updated_at' => now()
        //         ]);
        //     }
        // }
        $this->call(SubscriptionSeeder::class);
        $this->call(ResourceSeeder::class);
        $this->call(HelpfulSeeder::class);
        $this->call(QuizSeeder::class);
        $this->call(QuestionSeeder::class);
    }
}

