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
        $this->call([
            UserSeeder::class,
            UniversitySeeder::class,
            SubjectSeeder::class,
            TeacherSeeder::class,
            CourseSeeder::class,
            LectureSeeder::class,
            QuizSeeder::class,
            QuestionSeeder::class,
            SubscriptionSeeder::class,
            ResourceSeeder::class,
            AdminSeeder::class,
            TeacherRequestSeeder::class,
            FavouriteSeeder::class,
            CourseRatingSeeder::class,
        ]);
    }
}
