<?php

namespace App\Services\Teachers;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeacherService
{
    public function fetch($id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json([
                'success' => "false",
                'reason' => "Teacher Not Found"
            ], 404);
        }

        return response()->json([
            'success' => "true",
            'teacher' => $teacher
        ]);
    }

    public function fetchCourses($id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'reason' => "Teacher Not Found"
            ], 404);
        }

        $courses = $teacher->courses;
        $courses->each(function ($course) {
            $course->isFavorite = Auth::user()->favoriteCourses()
                ->where('course_id', $course->id)
                ->exists();
            $course->rating = $course->ratings()->avg('rating') ?? null;
        });

        return response()->json([
            'success' => true,
            'courses' => $courses
        ]);
    }

    public function fetchCoursesNames($id)
    {
        $courses = "";
        $teacher = Teacher::find($id);
        if ($teacher) {
            $teacherCourses = $teacher->courses()->get();
            $count = $teacherCourses->count();
            foreach ($teacherCourses as $index => $course) {
                $courses .= $course->name;
                if ($index < $count - 1)
                    $courses .= " - ";
            }
            return response()->json([
                'success' => "true",
                'courses' => $courses
            ]);
        } else {
            return response()->json([
                'success' => "false",
                'reason' => "Teacher Not Found"
            ]);
        }
    }

    public function fetchSubjects($id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'reason' => "Teacher Not Found"
            ], 404);
        }

        return response()->json([
            'success' => true,
            'subjects' => $teacher->subjects
        ]);
    }

    public function fetchSubjectsNames($id)
    {
        $subjects = "";
        $teacher = Teacher::find($id);
        if ($teacher) {
            $count = $teacher->subjects->count();
            foreach ($teacher->subjects as $index => $subject) {
                $subjects .= $subject->name;
                if ($index < $count - 1)
                    $subjects .= " - ";
            }
            return response()->json([
                'success' => "true",
                'subjects' => $subjects
            ]);
        } else {
            return response()->json([
                'success' => "false",
                'reason' => "Teacher Not Found"
            ], 404);
        }
    }

    public function fetchAll()
    {
        return response()->json([
            'teachers' => Teacher::all()
        ]);
    }

    public function fetchRecentCourses($id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'reason' => "Teacher Not Found"
            ], 404);
        }

        $courses = $teacher->courses()->latest()->take(3)->get();
        $courses->each(function ($course) {
            $course->isFavorite = Auth::user()->favoriteCourses()
                ->where('course_id', $course->id)
                ->exists();
            $course->rating = $course->ratings()->avg('rating') ?? null;
        });

        return response()->json([
            'success' => true,
            'courses' => $courses
        ]);
    }

    public function fetchTopRatedCourses($id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'reason' => "Teacher Not Found"
            ], 404);
        }

        $courses = $teacher->courses()
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(3)
            ->get();

        $courses->each(function ($course) {
            $course->isFavorite = Auth::user()->favoriteCourses()
                ->where('course_id', $course->id)
                ->exists();
        });

        return response()->json([
            'success' => true,
            'courses' => $courses
        ]);
    }

    public function fetchRatings($id)
    {
        $ratings = DB::table('teacher_ratings')->where('teacher_id', $id)->where('isHidden', false)->get();
        return response()->json([
            'ratings' => $ratings
        ]);
    }

    public function checkFavoriteTeacher($id)
    {
        $isFavorited = Auth::user()->favoriteTeachers()
            ->where('teacher_id', $id)
            ->exists();

        return response()->json([
            'is_favorited' => $isFavorited
        ]);
    }

    public function rate($id, $rating, $review = null)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'reason' => "Teacher Not Found"
            ], 404);
        }

        $teacher->ratings()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['rating' => $rating, 'review' => $review]
        );

        return response()->json([
            'success' => true,
            'teacher' => $teacher->fresh()
        ]);
    }
    public function add($data, $file = null)
    {
        if ($file) {
            $directory = 'Images/Teachers';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }
            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;
        } else {
            $path = "Images/Teachers/default.png";
        }

        $teacher = Teacher::create([
            'name' => $data['name'],
            'image' => $path,
            'subscriptions' => 0
        ]);

        return response()->json([
            'success' => true,
            'teacher' => $teacher
        ]);
    }

    public function edit($id, $data, $file = null)
    {
        $teacher = Teacher::findOrFail($id);

        if ($file) {
            $directory = 'Images/Teachers';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }
            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;

            if ($teacher->image != "Images/Teachers/default.png" && file_exists(public_path($teacher->image))) {
                unlink(public_path($teacher->image));
            }

            $teacher->image = $path;
        }

        $teacher->name = $data['name'];
        $teacher->save();

        return redirect()->route('update.confirmation');
    }

    public function delete($id)
    {
        $teacher = Teacher::findOrFail($id);

        if ($teacher->image != "Images/Teachers/default.png" && file_exists(public_path($teacher->image))) {
            unlink(public_path($teacher->image));
        }

        $teacher->delete();

        return redirect()->route('delete.confirmation');
    }
}