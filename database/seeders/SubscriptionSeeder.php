<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            Subscription::factory()->create([
                'user_id' => rand(1, User::count() - 1),
                $courseID = 'course_id' => rand(1, Course::count()),
                'created_at' => now()->startOfMonth()->addSeconds(rand(0, now()->endOfMonth()->diffInSeconds(now()->startOfMonth()))),
            ]);

        }

        foreach (Course::all() as $course) {
            $course->subscriptions = Course::withCount('users')->find($course->id)->users_count;
            $course->save();
        }

        $user = User::where('userName', 'username')->first();
        $courses = Subject::find(5)->courses()->get();
        foreach ($courses as $course) {
            Subscription::factory()->create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'created_at' => now()->startOfMonth()->addSeconds(rand(0, now()->endOfMonth()->diffInSeconds(now()->startOfMonth()))),
            ]);
            $course->increment('subscriptions');
        }

        $courses = Subject::find(10)->courses()->get();
        foreach ($courses as $course) {
            Subscription::factory()->create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'created_at' => now()->startOfMonth()->addSeconds(rand(0, now()->endOfMonth()->diffInSeconds(now()->startOfMonth()))),
            ]);
            $course->increment('subscriptions');
        }
        //
    }
}
