<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\helpful;

class HelpfulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $courseRatings = DB::table('course_rating')->pluck('id');
        $lectureRatings = DB::table('lecture_rating')->pluck('id');
        $teacherRatings = DB::table('teacher_ratings')->pluck('id');
        $resourceRatings = DB::table('resources_ratings')->pluck('id');

        foreach ($users as $user) {
            $helpfuls = [];
            $usedCourse = [];
            $usedLecture = [];
            $usedTeacher = [];
            $usedResource = [];
            for ($i = 0; $i < 10; $i++) {
                $type = rand(0, 3); // 0: course, 1: lecture, 2: teacher, 3: resource
                $isHelpful = (bool)rand(0, 1);
                if ($type === 0 && count($courseRatings) > 0) {
                    $ratingId = $courseRatings->random();
                    if (in_array($ratingId, $usedCourse)) { $i--; continue; }
                    $usedCourse[] = $ratingId;
                    helpful::create([
                        'user_id' => $user->id,
                        'course_rating_id' => $ratingId,
                        'isHelpful' => $isHelpful,
                    ]);
                } elseif ($type === 1 && count($lectureRatings) > 0) {
                    $ratingId = $lectureRatings->random();
                    if (in_array($ratingId, $usedLecture)) { $i--; continue; }
                    $usedLecture[] = $ratingId;
                    helpful::create([
                        'user_id' => $user->id,
                        'lecture_rating_id' => $ratingId,
                        'isHelpful' => $isHelpful,
                    ]);
                } elseif ($type === 2 && count($teacherRatings) > 0) {
                    $ratingId = $teacherRatings->random();
                    if (in_array($ratingId, $usedTeacher)) { $i--; continue; }
                    $usedTeacher[] = $ratingId;
                    helpful::create([
                        'user_id' => $user->id,
                        'teacher_rating_id' => $ratingId,
                        'isHelpful' => $isHelpful,
                    ]);
                } elseif ($type === 3 && count($resourceRatings) > 0) {
                    $ratingId = $resourceRatings->random();
                    if (in_array($ratingId, $usedResource)) { $i--; continue; }
                    $usedResource[] = $ratingId;
                    helpful::create([
                        'user_id' => $user->id,
                        'resource_rating_id' => $ratingId,
                        'isHelpful' => $isHelpful,
                    ]);
                } else {
                    $i--;
                }
            }
        }
    }
}
