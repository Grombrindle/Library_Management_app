<?php

namespace App\Observers;

use App\Models\Subscription;
use App\Services\FirebaseNotificationService;
use Log;

class SubscriptionObserver
{
    /**
     * Handle the Subscription "created" event.
     */
    public function created(Subscription $subscription): void
    {
        //

        $user = $subscription->user()->first();
        $course = $subscription->course()->first();

        if ($user && $course) {
            FirebaseNotificationService::sendToUser(
                $user,
                "You are now subscribed to {$course->name}",
                "You can now access the content of this course. Enjoy!"
            );
            Log::info("Ovserver Triggered: Course '{$user->userName}' has subscribed to {$course->name}");

        }

    }

    /**
     * Handle the Subscription "updated" event.
     */
    public function updated(Subscription $subscription): void
    {
        //
    }

    /**
     * Handle the Subscription "deleted" event.
     */
    public function deleted(Subscription $subscription): void
    {
        //
    }

    /**
     * Handle the Subscription "restored" event.
     */
    public function restored(Subscription $subscription): void
    {
        //
    }

    /**
     * Handle the Subscription "force deleted" event.
     */
    public function forceDeleted(Subscription $subscription): void
    {
        //
    }
}
