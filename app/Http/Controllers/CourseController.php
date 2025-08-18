<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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

    public function fetchHomePage()
    {
        // Use caching to improve performance for frequently accessed data
        $cacheKey = 'homepage_courses_' . (Auth::id() ?? 'guest');
        $cacheDuration = 300; // 5 minutes

        return Cache::remember($cacheKey, $cacheDuration, function () {
            // Get recommended courses (using the existing algorithm) - only essential fields
            $recommendedCourses = Course::select(['id', 'name', 'image'])
                ->withCount(['users', 'ratings', 'lectures'])
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
                ->limit(7)
                ->get();

            // Get top-rated courses - only essential fields
            $topRatedCourses = Course::select(['id', 'name', 'image'])
                ->withAvg('ratings', 'rating')
                ->orderByDesc('ratings_avg_rating')
                ->limit(7)
                ->get();

            // Get most-subscribed courses - only essential fields
            $mostSubscribedCourses = Course::select(['id', 'name', 'image'])
                ->withCount('users')
                ->orderByDesc('users_count')
                ->limit(7)
                ->get();

            // Get recent courses - only essential fields
            $recentCourses = Course::select(['id', 'name', 'image'])
                ->orderByDesc('created_at')
                ->limit(7)
                ->get();

            // Get user-subscribed courses (if user is authenticated) - only essential fields
            $userSubscribedCourses = null;
            if (Auth::check()) {
                $userSubscribedCourses = Auth::user()->courses()
                    ->select('courses.id', 'courses.name', 'courses.image')
                    ->limit(7)
                    ->get();
            }

            return response()->json([
                'success' => true,
                'subjects' => Subject::select(['id', 'name', 'literaryOrScientific', 'image'])->get()->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'literaryOrScientific' => $subject->literaryOrScientific,
                        'image' => $subject->image,
                        'imageUrl' => url($subject->image),
                    ];
                }),
                'recommended' => $recommendedCourses,
                'top_rated' => $topRatedCourses,
                'most_subscribed' => $mostSubscribedCourses,
                'recent' => $recentCourses,
                'user_subscribed' => $userSubscribedCourses,
            ]);
        });
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
        if ($request->input('course_price') <= 0) {
            return back()->withErrors(['course_price' => 'Course price must be greater than 0']);
        }

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

            // Calculate sparkies price based on course price
            $sparkiesPrice = 0;
            if ($request->input('course_paid')) {
                $coursePrice = $request->input('course_price');
                if ($coursePrice <= 5) {
                    $sparkiesPrice = 1;
                } elseif ($coursePrice <= 10) {
                    $sparkiesPrice = 2;
                } else {
                    $sparkiesPrice = 3;
                }
            }

            $course = Course::make([
                'name' => $request->input('course_name'),
                'teacher_id' => $request->input('teacher'),
                'subject_id' => $request->input('subject'),
                'description' => $request->input('course_description'),
                'lecturesCount' => 0,
                'subscriptions' => 0,
                'sources' => $sources ? json_encode($sources) : null,
                'price' => $request->input('course_price'),
                'sparkies' => $request->input('course_paid') ? true : false,
                'sparkiesPrice' => $sparkiesPrice,
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
                // Calculate sparkies price based on course price for course request
                $sparkiesPrice = 0;
                if ($request->input('course_paid')) {
                    $coursePrice = $request->input('course_price');
                    if ($coursePrice <= 5) {
                        $sparkiesPrice = 1;
                    } elseif ($coursePrice <= 10) {
                        $sparkiesPrice = 2;
                    } else {
                        $sparkiesPrice = 3;
                    }
                }

                $courseRequestData = [
                    'teacher_id' => Auth::user()->teacher_id,
                    'name' => $request->input('course_name'),
                    'description' => $request->input('course_description', null),
                    'subject_id' => $request->input('subject'),
                    'image' => $requestImagePath,
                    'sources' => $sources ? json_encode($sources) : null,
                    'requirements' => $requirements,
                    'price' => $request->input('course_price', null),
                    'sparkies' => $request->input('course_paid') ? true : false,
                    'sparkiesPrice' => $sparkiesPrice,
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


        // Handle sources input (stringified JSON or array)
        $sourcesInput = $request->input('sources', []);
        if (is_string($sourcesInput)) {
            $sourcesDecoded = json_decode($sourcesInput, true);
            $sources = is_array($sourcesDecoded) ? $sourcesDecoded : [];
        } else {
            $sources = $sourcesInput;
        }

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
        // Calculate sparkies price based on course price
        $sparkiesPrice = 0;
        if ($request->input('course_paid')) {
            $coursePrice = $request->input('course_price');
            if ($coursePrice <= 5) {
                $sparkiesPrice = 1;
            } elseif ($coursePrice <= 10) {
                $sparkiesPrice = 2;
            } else {
                $sparkiesPrice = 3;
            }
        }

        $course->name = $request->input('course_name');
        $course->description = $request->input('course_description');
        $course->sources = $sources ? json_encode($sources) : null;
        $course->price = $request->input('course_price');
        $course->sparkies = $request->input('course_paid') ? true : false;
        $course->sparkiesPrice = $sparkiesPrice;
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
    public function purchaseCourse(Request $request, $id)
    {
        $user = Auth::user();
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['success' => false, 'message' => 'Course not found'], 404);
        }

        if ($user->courses()->where('course_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Already purchased'], 400);
        }

        // Check if course is purchasable with sparkies
        if (!$course->sparkies) {
            return response()->json(['success' => false, 'message' => 'Course is not purchasable with sparkies'], 400);
        }

        $sparkiesPrice = (int) $course->sparkiesPrice;
        if ($user->sparkies < $sparkiesPrice) {
            return response()->json(['success' => false, 'message' => 'Insufficient sparkies'], 400);
        }

        // Deduct sparkies and subscribe
        $user->sparkies -= $sparkiesPrice;
        $user->save();
        $user->courses()->attach($id);

        return response()->json(['success' => true, 'message' => 'Course purchased successfully']);
    }

    /**
     * Set the price of a course (admin only).
     */
    // public function setCoursePrice(Request $request, $courseId)
    // {
    //     $user = Auth::user();
    //     if (!$user || !$user->is_admin) {
    //         return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    //     }
    //     $course = Course::find($courseId);
    //     if (!$course) {
    //         return response()->json(['success' => false, 'message' => 'Course not found'], 404);
    //     }
    //     $validated = $request->validate([
    //         'price' => 'required|integer|min:0',
    //     ]);
    //     $course->price = $validated['price'];
    //     $course->save();
    //     return response()->json(['success' => true, 'message' => 'Course price updated', 'course' => $course]);
    // }

    /**
     * Unified endpoint for all course categories.
     * @return \Illuminate\Http\JsonResponse
     */
    public function coursesOverview()
    {
        $recommended = $this->fetchAllRecommended()->getData(true)['courses'] ?? [];
        $topRated = $this->fetchAllRated()->getData(true)['courses'] ?? [];
        $recent = $this->fetchAllRecent()->getData(true)['courses'] ?? [];
        $all = $this->fetchall()->getData(true)['courses'] ?? [];
        return response()->json([
            'recommendedCourses' => $recommended,
            'topRatedCourses' => $topRated,
            'recentCourses' => $recent,
            'allCourses' => $all,
        ]);
    }
}
