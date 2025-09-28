<?php

namespace App\Services\Subjects;

use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectService
{
    public function fetch($id)
    {
        $subject = Subject::find($id);


        if ($subject) {
            return [
                'success' => "true",
                'subject' => $subject,
                'status' => 200
            ];
        }

        return [
            'success' => "false",
            'reason' => "Subject Not Found",
            'status' => 404
        ];
    }

    // public function fetchLectures($id)
    // {
    //     $subject = Subject::find($id);

    //     if ($subject) {
    //         $lectures = $subject->lectures->map(function ($lecture) {
    //             return [
    //                 'id' => $lecture->id,
    //                 'name' => $lecture->name,
    //                 'file_360' => asset($lecture->file_360),
    //                 'file_720' => asset($lecture->file_720),
    //                 'file_1080' => asset($lecture->file_1080),
    //                 'description' => $lecture->description,
    //                 'image' => $lecture->image,
    //                 'subject_id' => $lecture->subject_id,
    //                 'created_at' => $lecture->created_at,
    //                 'updated_at' => $lecture->updated_at,
    //             ];
    //         });

    //         return [
    //             'success' => true,
    //             'lectures' => $lectures,
    //             'status' => 200
    //         ];
    //     }

    //     return [
    //         'success' => false,
    //         'reason' => "Subject Not Found",
    //         'status' => 404
    //     ];
    // }

    public function fetchTeachers($id)
    {
        $subject = Subject::find($id);

        if ($subject) {
            $teachers = $subject->teachers;

            $teachers->each(function ($teacher) {
                $teacher->isFavorite = Auth::user()->favoriteTeachers()
                    ->where('teacher_id', $teacher->id)
                    ->exists();
            });

            return [
                'success' => "true",
                'teachers' => $teachers,
                'status' => 200
            ];
        }

        return [
            'success' => "false",
            'reason' => "Subject Not Found",
            'status' => 404
        ];
    }

    public function fetchAll()
    {
        return [
            'subjects' => Subject::all(),
            'status' => 200
        ];
    }

    public function fetchScientific()
    {
        return [
            'subjects' => Subject::where('literaryOrScientific', 1)->get(),
            'status' => 200
        ];
    }

    public function fetchLiterary()
    {
        return [
            'subjects' => Subject::where('literaryOrScientific', 0)->get(),
            'status' => 200
        ];
    }

    public function add($request)
    {
        if (!is_null($request->file('object_image'))) {
            $file = $request->file('object_image');
            $directory = 'Images/Subjects';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;
        } else {
            $path = "Images/Subjects/default.png";
        }

        $subject = Subject::create([
            'name' => $request->input('subject_name'),
            'image' => $path,
            'literaryOrScientific' => $request->input('subject_type') == 'on' ? 1 : 0
        ]);

        return [
            'success' => true,
            'subject' => $subject,
            'status' => 200
        ];
    }
    public function edit($request, $id)
    {
        $validator = $request->validate([
            'subject_name' => [
                \Illuminate\Validation\Rule::unique('subjects', 'name')->ignore($id)
            ],
        ]);

        if (!$validator) {
            return [
                'error' => ['subject_name' => 'Name has already been taken']
            ];
        }

        $subject = Subject::findOrFail($id);

        if (!is_null($request->file('object_image'))) {
            $file = $request->file('object_image');
            $directory = 'Images/Subjects';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;

            if ($subject->image != "Images/Subjects/default.png" && file_exists(public_path($subject->image))) {
                unlink(public_path($subject->image));
            }

            $subject->image = $path;
        }

        $teachers = json_decode($request->selected_objects, true);
        $subject->teachers()->sync($teachers);
        $subject->name = $request->subject_name;
        $subject->save();

        return $subject;
    }

    public function delete($id)
    {
        $subject = Subject::findOrFail($id);
        $name = $subject->name;

        if ($subject->image != "Images/Subjects/default.png" && file_exists(public_path($subject->image))) {
            unlink(public_path($subject->image));
        }

        $subject->delete();

        return $name;
    }
}