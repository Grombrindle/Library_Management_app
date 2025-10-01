<?php

namespace App\Actions\Subjects;

use App\Models\Subject;
use Illuminate\Http\Request;

class AddSubjectAction {

    public function execute(Request $request) {

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

}