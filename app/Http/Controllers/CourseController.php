<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;

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
            ->with(['subject' => function ($query) {
                $query->select('id', 'name', 'literaryOrScientific');
            }])
            ->get();

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
        return response()->json([
            'courses' => Course::count() ? Course::all() : null,
        ]);
    }

    public function fetchTeacher($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false, // Changed to boolean
                'reason' => "Course Not Found"
            ], 404);
        }

        return response()->json([
            'success' => true,
            'teachers' => $course->teacher->count() ? $course->teacher : null,
        ]);
    }

    public function add(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'university_name' => 'unique:universities,name'
        // ], [
        //     'university_name.unique' => "Already Used"
        // ]);
        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors(['university_name' => "Name Has Already Been Taken"])->withInput(["university_name"]);
        // }

        if (!is_null($request->file('object_image'))) {
            // Store new image in public/Images/Universities
            $file = $request->file('object_image');
            $directory = 'Images/Courses';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            // Ensure directory exists (create if needed)
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            // Store the new image
            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;  // "Images/Universities/filename.ext"
        } else {
            // Use default image
            $path = "Images/Courses/default.png";
        }

        // dd(Request::all());


        if ($request->input('teacher_id') != null)
            $course = Course::make(['name' => $request->input('course_name'), 'teacher_id' => $request->input('teacher'), 'subject_id' => $request->input('subject'), 'lecturesCount' => 0, 'subscriptions' => 0]);
        elseif (Auth::user()->privileges == 0)
            $course = Course::make(['name' => $request->input('course_name'), 'teacher_id' => Auth::user()->teacher_id, 'subject_id' => $request->input('subject'), 'lecturesCount' => 0, 'subscriptions' => 0]);

        $course->image = $path;
        $course->save();
        $data = ['element' => 'course', 'id' => $course->id, 'name' => $course->name];
        session(['add_info' => $data]);
        session(['link' => '/courses']);
        return redirect()->route('add.confirmation');
    }

    public function edit(Request $request, $id)
    {
        // dd($request->all());
        // $uniAttributes = $request->validate([
        //     'university_name' => [
        //         Rule::unique('universities', 'name')->ignore($id),
        //     ],
        // ]);
        // if (!$uniAttributes) {
        //     return redirect()->back()->withErrors([
        //         'university_name' => 'Name has alread been taken.'
        //     ]);
        // }
        $course = Course::findOrFail($id);
        // $teachers = json_decode($request->selected_objects, true);
        // $course->teachers()->sync($teachers);
        if (!is_null($request->file('object_image'))) {
            // Store new image in public/Images/Universities
            $file = $request->file('object_image');
            $directory = 'Images/Courses';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            // Ensure directory exists
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            // Store the new image
            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;

            // Delete old image if it's not the default
            if ($course->image != "Images/courseversities/default.png" && file_exists(public_path($course->image))) {
                unlink(public_path($course->image));
            }

            $course->image = $path;
        }
        $course->name = $request->input('course_name');
        $course->description = $request->input('course_description');
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
