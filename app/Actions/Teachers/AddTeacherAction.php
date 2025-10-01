<?php

namespace App\Actions\Teachers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;


class AddTeacherAction {

    public function execute(Request $request, $file = null) {
        // dd($request->all());
        if (!is_null($request->file('object_image'))) {
            // Handle new image upload
            $file = $request->file('object_image');
            $directory = 'Images/Admins';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            // Ensure directory exists
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            // Store the image in public folder
            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;  // Will be "Images/Admins/filename.jpg"
        } else {
            // Use default image
            $path = "Images/Admins/teacherDefault.png";
        }

            $userName = $request->input('teacher_user_name');
            $name = $request->input('teacher_name');
            $number = $request->input('teacher_number');
            $password = $request->input('teacher_password');


        $teacher = Teacher::create([
            'userName' => $userName,
            'name' => $name,
            'description' => $request->input('teacher_description'),
            'number' => $number,
            'countryCode' => '+963',
            'image' => $path,
            'password' => Hash::make($password)
        ]);
        $links = [
            'Facebook' => $request->input('facebook_link', ''),
            'Instagram' => $request->input('instagram_link', ''),
            'Telegram' => $request->input('telegram_link', ''),
            'YouTube' => $request->input('youtube_link', ''),
        ];

        // Convert the array to a JSON string
        $linksJson = json_encode($links);
        $teacher->links = $linksJson;
        $teacher->save();
        Admin::create([
            'userName' => $userName,
            'name' => $name,
            'countryCode' => '+963',
            'number' => $number,
            'password' => Hash::make($password),
            'privileges' => 0,
            'teacher_id' => $teacher->id,
            'image' => $path,
        ]);

        $data = ['element' => 'taecher', 'id' => $teacher->id, 'name' => $teacher->name];
        session(['add_info' => $data]);
        session(['link' => '/teachers']);
        return redirect()->route('add.confirmation');
    }
}