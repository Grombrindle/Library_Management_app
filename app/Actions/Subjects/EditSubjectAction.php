<?php

namespace App\Actions\Subjects;

use App\Models\Subject;
use Illuminate\Http\Request;

class EditSubjectAction {

    public function execute(Request $request, $id) {
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

}