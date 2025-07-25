<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function getTeacherCourses($teacherId)
    {
        $teacher = Teacher::find($teacherId);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ], 404);
        }

        $courses = $teacher->courses()
            ->with([
                'subject' => function ($query) {
                    $query->select('id', 'name', 'literaryOrScientific');
                }
            ])
            ->get()
            ->map(function ($course) {
                $course->sources = json_decode($course->sources, true);
                return $course;
            });

        return response()->json([
            'success' => true,
            'courses' => $courses,
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name
            ]
        ]);
    }

    public function fetch($id)
    {
        $course = Course::find($id);
        if ($course) {
            $course->sources = json_decode($course->sources, true);
            return response()->json([
                'success' => "true",
                'course' => $course,
            ]);
        } else {
            return response()->json([
                'success' => "false",
                'reason' => "Course Not Found"
            ]);
        }
    }

    public function fetchall()
    {
        $courses = Course::count() ? Course::all() : null;

        if ($courses) {
            foreach ($courses as $course) {
                $course->rating = DB::table('course_rating')
                    ->where('course_id', $course->id)
                    ->avg('rating') ?? null;
                $course->sources = json_decode($course->sources, true);
            }
        }

        return response()->json([
            'courses' => $courses,
        ]);
    }

    public function fetchAllRecent()
    {
        $courses = Course::withAvg('ratings', 'rating')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($course) {
                $course->sources = json_decode($course->sources, true);
                return $course;
            });

        return response()->json([
            'courses' => $courses,
        ]);
    }

    public function fetchAllRated()
    {
        $courses = Course::withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->get()
            ->map(function ($course) {
                $course->sources = json_decode($course->sources, true);
                return $course;
            });

        return response()->json([
            'courses' => $courses,
        ]);
    }

    public function fetchAllSubscribed()
    {
        $courses = Course::withCount('users')
            ->orderByDesc('users_count')
            ->get()
            ->map(function ($course) {
                $course->sources = json_decode($course->sources, true);
                return $course;
            });

        return response()->json([
            'courses' => $courses,
        ]);
    }

    public function fetchAllRecommended()
    {
        $courses = Course::withCount(['users', 'ratings', 'lectures'])
            ->withAvg('ratings', 'rating')
            ->orderByDesc(DB::raw('
                (
                    (COALESCE(ratings_avg_rating, 0) * 0.5) +
                    (ratings_count * 0.2) +
                    (users_count * 0.2) +
                    (lectures_count * 0.1)
                ) *
                (1 + (COALESCE(ratings_avg_rating, 0) / 5))
            '))
            ->get()
            ->map(function ($course) {
                $course->sources = json_decode($course->sources, true);
                return $course;
            });

        return response()->json([
            'courses' => $courses,
        ]);
    }

    public function fetchAllUserSubscribed()
    {
        $courses = Auth::user()->courses()
            ->withCount('users')
            ->withAvg('ratings', 'rating')
            ->get()
            ->map(function ($course) {
                $course->sources = json_decode($course->sources, true);
                return $course;
            });

        return response()->json([
            'courses' => $courses,
        ]);
    }

    public function fetchTeacher($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'reason' => "Course Not Found"
            ], 404);
        }

        $course->sources = json_decode($course->sources, true);

        return response()->json([
            'success' => true,
            'teachers' => $course->teacher->count() ? $course->teacher : null,
            'course' => $course
        ]);
    }

    public function checkFavoriteCourse($id)
    {
        $isFavorited = Auth::user()->favoriteCourses()
            ->where('course_id', $id)
            ->exists();

        return response()->json([
            'is_favorited' => $isFavorited
        ]);
    }

    public function fetchRatings($id)
    {
        $ratings = DB::table('course_rating')->where('course_id', $id)->get();
        return response()->json([
            'ratings' => $ratings
        ]);
    }

    public function rate(Request $request, $id)
    {
        $course = Course::find($id);

        if ($course) {
            $rate = DB::table('course_rating')->updateOrInsert(
                [
                    'user_id' => Auth::user()->id,
                    'course_id' => $id
                ],
                [
                    'rating' => $request->input('rating'),
                    'review' => $request->input('review'),
                    'updated_at' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Rating saved successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Course not found'
        ], 404);
    }

    public function add(Request $request)
    {
        $imagePath = "Images/Courses/default.png";
        $requestImagePath = null;
        if (!is_null($request->file('object_image'))) {
            $file = $request->file('object_image');
            $directory = 'Images/CourseRequests';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }
            $file->move(public_path($directory), $filename);
            $requestImagePath = $directory . '/' . $filename;
            $imagePath = $directory . '/' . $filename;
        } else {
            $requestImagePath = null;
        }


        if ($request->input('teacher') != null) {

            $sourcesInput = $request->input('sources', []);
            // If sources is a string, decode it; if already array/object, keep as is
            if (is_string($sourcesInput)) {
                $sourcesDecoded = json_decode($sourcesInput, true);
                $sources = is_array($sourcesDecoded) ? $sourcesDecoded : [];
            } else {
                $sources = $sourcesInput;
            }

            $requirementsInput = $request->input('requirements', []);
            if (is_string($requirementsInput)) {
                $requirementsDecoded = json_decode($requirementsInput, true);
                $requirements = is_array($requirementsDecoded) ? $requirementsDecoded : [];
            } else {
                $requirements = $requirementsInput;
            }

            $course = Course::make([
                'name' => $request->input('course_name'),
                'teacher_id' => $request->input('teacher'),
                'subject_id' => $request->input('subject'),
                'description' => $request->input('course_description'),
                'lecturesCount' => 0,
                'subscriptions' => 0,
                'sources' => $sources ? json_encode($sources) : null,
                'requirements' => $requirements
            ]);
            $course->image = $imagePath;
            $course->save();

            $data = ['element' => 'course', 'id' => $course->id, 'name' => $course->name];
            session(['add_info' => $data]);
            session(['link' => '/courses']);
            return redirect()->route('add.confirmation');
        } else {

            // If teacher, also create a course request
            if (Auth::user()->privileges == 0) {
                $sourcesInput = $request->input('sources', []);
                if (is_string($sourcesInput)) {
                    $sourcesDecoded = json_decode($sourcesInput, true);
                    $sources = is_array($sourcesDecoded) ? $sourcesDecoded : [];
                } else {
                    $sources = $sourcesInput;
                }
                $requirementsInput = $request->input('requirements', []);
                if (is_string($requirementsInput)) {
                    $requirementsDecoded = json_decode($requirementsInput, true);
                    $requirements = is_array($requirementsDecoded) ? $requirementsDecoded : [];
                } else {
                    $requirements = $requirementsInput;
                }
                $courseRequestData = [
                    'teacher_id' => Auth::user()->teacher_id,
                    'name' => $request->input('course_name'),
                    'description' => $request->input('course_description', null),
                    'subject_id' => $request->input('subject'),
                    'image' => $requestImagePath,
                    'sources' => json_encode($sources),
                    'requirements' => $requirements,
                    'price' => $request->input('price', null),
                    'status' => 'pending',
                    'admin_id' => null,
                    'course_id' => $request->input('id'),
                    'rejection_reason' => null,
                    'lecturesCount' => $request->input('lecturesCount'),
                    'subscriptions' => $request->input('subscriptions'),
                ];
                \App\Models\CourseRequest::create($courseRequestData);
            }
            $data = ['element' => 'course', 'id' => $request->input('id'), 'name' => $request->input('course_name')];
            session(['add_info' => $data]);
            session(['link' => '/courses']);
            return redirect()->route('add.confirmation');
        }
    }

    public function edit(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        if (!is_null($request->file('object_image'))) {
            $file = $request->file('object_image');
            $directory = 'Images/Courses';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;

            if ($course->image != "Images/Courses/default.png" && file_exists(public_path($course->image))) {
                unlink(public_path($course->image));
            }

            $course->image = $path;
        }
        $course->name = $request->input('course_name');
        $course->description = $request->input('course_description');
        $course->sources = json_encode($request->input('sources', []));
        $course->save();
        $data = ['element' => 'course', 'id' => $id, 'name' => $course->name];
        session(['update_info' => $data]);
        session(['link' => '/courses']);
        return redirect()->route('update.confirmation');
    }

    public function delete($id)
    {
        $course = Course::findOrFail($id);
        $name = $course->name;

        // Delete old image if it's not the default
        if ($course->image != "Images/Courses/default.png" && file_exists(public_path($course->image))) {
            unlink(public_path($course->image));
        }

        $course->delete();

        $data = ['element' => 'course', 'name' => $name];
        session(['delete_info' => $data]);
        session(['link' => '/courses']);
        return redirect()->route('delete.confirmation');
    }
}
