<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function fetch($id)
    {
        $subject = Subject::find($id);
        if ($subject) {
            return response()->json([
                'success' => "true",
                'subject' => $subject
            ]);
        } else {
            return response()->json([
                'success' => "false",
                'reason' => "Subject Not Found"
            ], 404);
        }
    }

    public function fetchLectures($id)
    {
        $subject = Subject::find($id);
        if ($subject) {
            $lectures = $subject->lectures->map(function ($lecture) {
                return [
                    'id' => $lecture->id,
                    'name' => $lecture->name,
                    'file_360' => asset($lecture->file_360),
                    'file_720' => asset($lecture->file_720),
                    'file_1080' => asset($lecture->file_1080),
                    'description' => $lecture->description,
                    'image' => $lecture->image,
                    'subject_id' => $lecture->subject_id,
                    'created_at' => $lecture->created_at,
                    'updated_at' => $lecture->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'lectures' => $lectures,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'reason' => "Subject Not Found"
            ], 404);
        }
    }

    public function fetchTeachers($id)
    {
        $subject = Subject::find($id);
        if ($subject) {
            return response()->json([
                'success' => "true",
                'teachers' => $subject->teachers,
            ]);
        } else {
            return response()->json([
                'success' => "false",
                'reason' => "Subject Not Found"
            ], 404);
        }
    }

    public function fetchAll()
    {
        return response()->json([
            'subjects' => Subject::all(),
        ]);
    }

    public function fetchScientific()
    {
        return response()->json([
            'subjects' => Subject::where('literaryOrScientific', 1)->get(),
        ]);
    }

    public function fetchLiterary()
    {
        return response()->json([
            'subjects' => Subject::where('literaryOrScientific', 0)->get(),
        ]);
    }

    public function add(Request $request)
    {
        $validator = $request->validate([
            'subject_name' => [Rule::unique('subjects', 'name')],
            'subject_type' => 'required|in:0,1' // 0 for literary, 1 for scientific
        ]);

        if (!$validator) {
            return redirect()->back()->withErrors([
                'subject_name' => 'Name has already been taken',
            ]);
        }

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
            'lecturesCount' => 0,
            'subscriptions' => 0,
            'image' => $path,
            'literaryOrScientific' => $request->input('subject_type')
        ]);

        return response()->json([
            'success' => true,
            'subject' => $subject
        ]);
    }
    public function edit(Request $request, $id)
    {

        $validator = $request->validate([
            'subject_name' => [
                Rule::unique('subjects', 'name')->ignore($id)
            ],
        ]);
        if (!$validator) {

            return redirect()->back()->withErrors([
                'subject_name' => 'Name has already been taken',
            ]);
        }
        // dd($request->all());
        $subject = Subject::findOrFail($id);
        if (!is_null($request->file('object_image'))) {
            // Store new image in public/Images/Subjects
            $file = $request->file('object_image');
            $directory = 'Images/Subjects';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            // Ensure directory exists
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            // Store the new image
            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;

            // Delete old image if it's not the default
            if ($subject->image != "Images/Subjects/default.png" && file_exists(public_path($subject->image))) {
                unlink(public_path($subject->image));
            }

            $subject->image = $path;
        }

        $teachers = json_decode($request->selected_objects, true);
        $subject->teachers()->sync($teachers);
        $subject->name = $request->subject_name;
        $subject->save();
        $data = ['element' => 'subject', 'id' => $id, 'name' => $subject->name];
        session(['update_info' => $data]);
        session(['link' => '/subjects']);
        return redirect()->route('update.confirmation');

    }

    public function delete($id)
    {
        $subject = Subject::findOrFail($id);
        $name = $subject->name;

        if ($subject->image != "Images/Subjects/default.png" && file_exists(public_path($subject->image))) {
            unlink(public_path($subject->image));
        }

        $subject->delete();

        foreach (Subject::all() as $subject) {
            $subject->subscriptions = Subject::withCount('users')->find($subject->id)->users_count;
            $subject->save();
        }
        $data = ['element' => 'subject', 'name' => $name];
        session(['delete_info' => $data]);
        session(['link' => '/subjects']);
        return redirect()->route('delete.confirmation');
    }
}
