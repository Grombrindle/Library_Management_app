<?php

namespace App\Services;

use App\Models\User;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserService
{
    public function fetchAuth()
    {
        $user = User::find(Auth::id());


        $courses = "";
        $count = $user->courses ? $user->courses->count() : null;

        if ($count != null) {
            foreach ($user->courses as $index => $course) {
                $courses .= $course->name;
                if ($index < $count - 1) {
                    $courses .= " - ";
                }
            }
        }


        $user->subs = $courses;
        $user->lecturesNum = $user->lectures ? $user->lectures->count() : "";

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function fetch($id)
    {
        $user = User::find($id);
        return response()->json([
            'success' => (bool) $user,
            'User' => $user
        ]);
    }

    public function fetchCourses()
    {
        return response()->json([
            'success' => "true",
            'courses' => Auth::user()->courses
        ]);
    }

    public function fetchLectures()
    {
        return response()->json([
            'success' => "true",
            'lectures' => Auth::user()->lectures
        ]);
    }

    public function fetchSubs()
    {
        $user = Auth::user();
        $courses = "";
        $count = $user->courses ? $user->courses->count() : null;
        if ($count != null) {
            foreach ($user->courses as $index => $course) {
                $courses .= $course->name;
                if ($index < $count - 1) {
                    $courses .= " - ";
                }
            }
        }
        return response()->json([
            'success' => "true",
            'courses' => $courses,
            'lectures' => $user->lectures ? $user->lectures->count() : ""
        ]);
    }

    public function fetchAll()
    {
        return response()->json([
            'success' => "true",
            'users' => User::all()
        ]);
    }

    public function fetchFavoriteCourses()
    {
        return response()->json([
            'success' => "true",
            'favorites' => Auth::user()->favoriteCourses
        ]);
    }

    public function fetchFavoriteTeachers()
    {
        return response()->json([
            'success' => "true",
            'favorites' => Auth::user()->favoriteTeachers
        ]);
    }

    public function edit($id, $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => [
                Rule::unique('users', 'userName')->ignore($id),
            ],
            'user_number' => [
                Rule::unique('users', 'number')->ignore($id),
                Rule::unique('admins', 'number')
            ],
        ], [
            'user_name.unique' => __('messages.username_taken'),
            'user_number.unique' => __('messages.number_taken'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors(['user_name', 'user_number'])->withInput(['user_name', 'user_number']);
        }

        $user = User::findOrFail($id);
        $courses = json_decode($request->selected_objects, true);
        $lectures = json_decode($request->selected_lectures, true);
        $user->isBanned = $request->isBanned == "on" ? 1 : 0;

        if ($request->selected_lectures == null) {
            $lectures = $user->lectures->pluck('id')->toArray();
        }

        $user->courses()->sync($courses);
        $user->lectures()->sync($lectures);

        $user->userName = $request->user_name;
        $user->number = $request->user_number;
        $user->save();

        foreach (Course::all() as $course) {
            $course->subscriptions = Course::withCount('users')->find($course->id)->users_count;
            $course->save();
        }
        $data = ['element' => 'user', 'id' => $id, 'name' => $user->userName];
        session(['update_info' => $data]);
        session(['link' => '/users']);
        return redirect()->route('update.confirmation');
    }

    public function editCounter()
    {
        $isBanned = false;
        $isLoggedOut = false;
        $user = Auth::user();
        $counter = $user->counter;
        $now = now();
        $lastScreenshotTime = $user->last_screenshot_at;

        if ($lastScreenshotTime && Carbon::parse($lastScreenshotTime)->diffInDays($now) >= 1) {
            $user->counter = 1;
            $user->last_screenshot_at = Carbon::now();
        } else {
            $user->counter = $counter + 1;
            $user->last_screenshot_at = Carbon::now();
        }

        if ($user->counter >= 1 && $user->counter < 4) {
            $user->remember_token = null;
            $user->save();
            $user->tokens()->delete();
            $isLoggedOut = true;
        }

        if ($user->counter >= 4) {
            $user->counter = 0;
            $user->isBanned = true;
            $user->remember_token = null;
            $user->save();
            $user->tokens()->delete();
            $isBanned = true;
            $isLoggedOut = true;
        }

        if (!$lastScreenshotTime || $user->counter === 1) {
            $user->update(['last_screenshot_at' => Carbon::now()]);
        }

        $user->save();
        return response()->json([
            'success' => true,
            'counter' => $isBanned ? 4 : $user->counter,
            'isLoggedOut' => $isLoggedOut,
            'isBanned' => $isBanned
        ]);
    }

    public function confirmCourseSub($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                'success' => false,
                'reason' => "Course Not Found"
            ], 404);
        }

        return response()->json([
            'success' => true,
            'isSubscribed' => Auth::user()->courses->pluck('id')->contains($id)
        ]);
    }

    public function confirmLecSub($id)
    {
        $lecture = Lecture::find($id);
        if (!$lecture) {
            return response()->json([
                'success' => false,
                'reason' => "Lecture Not Found"
            ], 404);
        }

        $user = Auth::user();
        $isSubscribed = ($user->lectures->pluck('id')->contains($id) ||
            $user->courses->pluck('id')->contains($lecture->course_id));

        return response()->json([
            'success' => true,
            'isSubscribed' => $isSubscribed
        ]);
    }

    public function toggleFavoriteCourse(Course $course)
    {
        $user = Auth::user();
        if ($user->favoriteCourses()->where('course_id', $course->id)->exists()) {
            $user->favoriteCourses()->detach($course);
            return response()->json([
                'status' => 'removed',
                'is_favorited' => false,
                'favorites_count' => $user->favoriteCourses->count()
            ]);
        }

        $user->favoriteCourses()->attach($course);
        return response()->json([
            'status' => 'added',
            'is_favorited' => true,
            'favorites_count' => $user->favoriteCourses->count()
        ]);
    }

    public function toggleFavoriteTeacher(Teacher $teacher)
    {
        $user = Auth::user();
        if ($user->favoriteTeachers()->where('teacher_id', $teacher->id)->exists()) {
            $user->favoriteTeachers()->detach($teacher);
            return response()->json([
                'status' => 'removed',
                'is_favorited' => false,
                'favorites_count' => $user->favoriteTeachers->count()
            ]);
        }

        $user->favoriteTeachers()->attach($teacher);
        return response()->json([
            'status' => 'added',
            'is_favorited' => true,
            'favorites_count' => $user->favoriteTeachers->count()
        ]);
    }
    public function updateUsername($request)
    {
        $validator = Validator::make($request->all(), [
            'userName' => [
                Rule::unique('admins', 'userName'),
                Rule::unique('users', 'userName'),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => "false",
                'reason' => "Username Already Taken"
            ], 409);
        }

        Auth::user()->userName = $request->input('userName');
        Auth::user()->save();

        return response()->json([
            'success' => "true"
        ]);
    }

    public function updatePassword($request)
    {
        if (Hash::check($request->input('oldPassword'), Auth::user()->password)) {
            if ($request->input('newPassword') != null) {
                Auth::user()->password = Hash::make($request->input('newPassword'));
                Auth::user()->save();
                return response()->json([
                    'success' => 'true',
                ]);
            } else
                return response()->json([
                    'success' => "false",
                    'reason' => 'New Password Is Empty'
                ]);
        } else {
            return response()->json([
                'success' => "false",
                'reason' => "Password Doesn't Match"
            ]);
        }
    }

    public function updateNumber($request)
    {
        $validator = Validator::make($request->all(), [
            'number' => ['required', 'string', Rule::unique('users', 'number')],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => "false",
                'reason' => $validator->errors()->first()
            ], 422);
        }

        Auth::user()->number = $request->input('number');
        Auth::user()->save();

        return response()->json(['success' => "true"]);
    }

    public function updateAvatar($request)
    {
        Auth::user()->avatar = $request->input('avatar');

        if ($request->input('avatar') > 9) {
            Auth::user()->avatar = 0;
        }

        Auth::user()->save();

        return response()->json(['success' => "true"]);
    }

    public function deleteSubs()
    {
        if (Auth::user()->privileges != 2) {
            abort(403);
        }

        foreach (User::all() as $user) {
            DB::transaction(function () use ($user) {
                $user->lectures()->detach();
                $user->courses()->detach();
            });

            $user->remember_token = null;
            $user->save();
            $user->tokens()->delete();
        }

        session(['update_info' => ['name' => "delete subs"]]);
        session(['link' => '/users']);
        return redirect()->route('update.confirmation');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $name = $user->userName;
        $user->delete();

        // foreach (Course::all() as $course) {
        //     $course->subscriptions = Course::withCount('users')->find($course->id);
        //     $course->save();
        // }

        session(['delete_info' => ['element' => 'user', 'name' => $name]]);
        session(['link' => '/users']);
        return redirect()->route('delete.confirmation');
    }
}