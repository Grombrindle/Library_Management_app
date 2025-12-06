<?php

namespace App\Observers;

use App\Models\Course;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Facades\Log;

class CourseObserver
{
    /**
     * Handle the Course "created" event.
     */
    public function created(Course $course): void
    {
        //

        Log::info("Ovserver Triggered: Course '{$course->name}' was created with ID {$course->id}");

        $course->description = $course->description ?? "No description provided";
        $course->saveQuietly();


        $teacher = $course->teacher;
        $users = $teacher->favoritedByUsers;

        if($users->isEmpty()) {
            return;
        }

        foreach($users as $user) {
            FirebaseNotificationService::sendToUser(
                $user,
                'New Course Added',
                "A new course '{$course->name}' has been added by '{$teacher->name}'. Check it out!"
            );
        }
    }

    /**
     * Handle the Course "updated" event.
     */
    public function updated(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "deleted" event.
     */
    public function deleted(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "restored" event.
     */
    public function restored(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "force deleted" event.
     */
    public function forceDeleted(Course $course): void
    {
        //
    }
}
