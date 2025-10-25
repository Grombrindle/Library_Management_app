<?php

namespace App\Services;

use App\Models\Watchlist;
use App\Models\Lecture;
use App\Models\Course;
use App\Models\Resource;
use Illuminate\Support\Facades\Auth;

class WatchlistService
{
    public function fetchLectures()
    {
        return Watchlist::where('user_id', Auth::id())
            ->whereNotNull('lecture_id')
            ->get();
    }

    public function fetchCourses()
    {
        return Watchlist::where('user_id', Auth::id())
            ->whereNotNull('course_id')
            ->get();
    }

    public function fetchResources()
    {
        return Watchlist::where('user_id', Auth::id())
            ->whereNotNull('resource_id')
            ->get();
    }

    public function toggleLecture($id)
    {
        $user = Auth::user();
        $lecture = Lecture::findOrFail($id);

        if ($user->watchlist()->where('lecture_id', $id)->exists()) {
            $user->watchlist()->detach($id);
            $action = 'removed';
        } else {
            $user->watchlist()->attach($id);
            $action = 'added';
        }

        return [
            'action' => $action,
            'watchlist' => $this->fetchLectures()
        ];
    }

    public function toggleCourse($id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);

        if ($user->courseWatchlist()->where('course_id', $id)->exists()) {
            $user->courseWatchlist()->detach($id);
            $action = 'removed';
        } else {
            $user->courseWatchlist()->attach($id);
            $action = 'added';
        }
        return [
            'action' => $action,
            'courseWatchlist' =>  Watchlist::where('user_id', $user->id)->whereNull('resource_id')->get(),
            'watchlist' =>$user->watchlist()
        ];
    }

    public function toggleResource($id)
    {
        $user = Auth::user();
        $resource = Resource::findOrFail($id);

        if ($user->resourceWatchlist()->where('resource_id', $id)->exists()) {
            $user->resourceWatchlist()->detach($id);
            $action = 'removed';
        } else {
            $user->resourceWatchlist()->attach($id);
            $action = 'added';
        }

        return [
            'action' => $action,
            'resourceWatchlist' =>  Watchlist::where('user_id', $user->id)->whereNull('course_id')->get(),
            'watchlist' =>$user->watchlist()
        ];
    }
}