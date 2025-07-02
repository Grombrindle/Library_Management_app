<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Watchlist;

class WatchlistController extends Controller
{
    //

    public function fetchLectures()
    {
        return response()->json([
            'success' => true,
            'watchlist' => Watchlist::where('user_id', Auth::id())->whereNotNull('lecture_id')->get()
        ]);
    }

    public function fetchCourses()
    {
        return response()->json([
            'success' => true,
            'watchlist' => Watchlist::where('user_id', Auth::id())->whereNotNull('course_id')->get()
        ]);
    }

    public function toggleLecture($id)
    {
        $user = Auth::user();
        $lecture = \App\Models\Lecture::findOrFail($id);

        // Check if lecture is already in watchlist
        if ($user->watchlist()->where('lecture_id', $id)->exists()) {
            // Remove from watchlist
            $user->watchlist()->detach($id);
            $action = 'removed';
        } else {
            // Add to watchlist
            $user->watchlist()->attach($id);
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'action' => $action,
            'watchlist' => Watchlist::where('user_id', Auth::id())->whereNotNull('lecture_id')->get()
        ]);
    }

    public function toggleCourse($id)
    {
        $user = Auth::user();
        $course = \App\Models\Course::findOrFail($id);

        // Check if course is already in watchlist
        if ($user->watchlist()->where('course_id', $id)->exists()) {
            // Remove from watchlist
            $user->watchlist()->detach($id);
            $action = 'removed';
        } else {
            // Add to watchlist
            $user->watchlist()->attach($id);
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'action' => $action,
            'watchlist' => Watchlist::where('user_id', Auth::id())->whereNotNull('course_id')->get()
        ]);
    }
}
