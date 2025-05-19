<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i=0;$i<10;$i++) {
            Subscription::factory()->create([
                'user_id'=> rand(1, User::count()),
                $courseID = 'course_id'=> rand(1, Course::count()),
            ]);

        }

        foreach (Course::all() as $course) {
            $course->subscriptions = Course::withCount('users')->find($course->id)->users_count;
            $course->save();
        }
        //
    }
}
