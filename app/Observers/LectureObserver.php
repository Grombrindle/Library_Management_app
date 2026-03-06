<?php

namespace App\Observers;

use App\Models\Lecture;
use App\Models\Course;
use App\Services\FirebaseNotificationService;

class LectureObserver
{
    /**
     * Handle the Lecture "created" event.
     */
    public function created(Lecture $lecture): void
    {
        //


        $course = $lecture->course()->get()->first();

        $users = $course->users()->get();

        if($users->isEmpty()) {
            return;
        }

        foreach($users as $user) {
            FirebaseNotificationService::sendToUser(
                $user,
                "New Lecture in '{$course->name}'",
                "Lecture '{$lecture->name}' has been uploaded."
            );
        }
    }

    /**
     * Handle the Lecture "updated" event.
     */
    public function updated(Lecture $lecture): void
    {
        //
    }

    /**
     * Handle the Lecture "deleted" event.
     */
    public function deleted(Lecture $lecture): void
    {
        //
    }

    /**
     * Handle the Lecture "restored" event.
     */
    public function restored(Lecture $lecture): void
    {
        //
    }

    /**
     * Handle the Lecture "force deleted" event.
     */
    public function forceDeleted(Lecture $lecture): void
    {
        //
    }
}
