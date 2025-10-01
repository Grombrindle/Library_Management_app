<?php

namespace App\Actions\Teachers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class EditTeacherAction
{

    public function execute(Request $request, $id, $file = null)
    {
        $links = [
            'Facebook' => $request->input('facebook_link', ''),
            'Instagram' => $request->input('instagram_link', ''),
            'Telegram' => $request->input('telegram_link', ''),
            'YouTube' => $request->input('youtube_link', ''),
        ];

        // Convert the array to a JSON string
        $linksJson = json_encode($links);
        $teacher = Teacher::findOrFail($id);
        $subjects = json_decode($request->selected_objects, true);
        $teacher->subjects()->sync($subjects);
        $teacher->name = $request->teacher_name;
        $teacher->userName = $request->teacher_user_name;
        $teacher->countryCode = '+963';
        $teacher->number = $request->teacher_number;
        $teacher->links = $linksJson;
        if (!is_null($request->file('object_image'))) {
            // Store new image in public/Images/Teachers
            $file = $request->file('object_image');
            $directory = 'Images/Admins';  // Changed from Admins to Teachers
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            // Ensure directory exists
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            // Store the new image
            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;  // Will be "Images/Teachers/filename.jpg"

            // Delete old image if it's not the default
            if ($teacher->image != "Images/Admins/teacherDefault.png" && file_exists(public_path($teacher->image))) {
                unlink(public_path($teacher->image));
            }

            $teacher->description = $request->input('teacher_description');
            $teacher->image = $path;
        }
        $teacher->description = $request->input('teacher_description');
        $teacher->save();

        $teacher = Admin::where('teacher_id', $teacher->id)->first();
        $teacher->name = $request->teacher_name;
        $teacher->userName = $request->teacher_user_name;
        $teacher->number = $request->teacher_number;
        if (!is_null($request->file('object_image')))
            $teacher->image = $path;
        $teacher->save();
        $data = ['element' => 'teacher', 'id' => $id, 'name' => $teacher->name];
        session(['update_info' => $data]);
        session(['link' => '/teachers']);
        return redirect()->route('update.confirmation');
    }
}
