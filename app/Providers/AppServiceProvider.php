<?php

namespace App\Providers;

use App\Observers\CourseObserver;
use App\Observers\LectureObserver;
use App\Observers\SubscriptionObserver;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Subscription;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Course::observe(CourseObserver::class);
        Lecture::observe(LectureObserver::class);
        Subscription::observe(SubscriptionObserver::class);
    }
}
