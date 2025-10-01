<?php

namespace App\Actions\Teachers;

use App\Models\Teacher;
use App\Models\Admin;

class DeleteTeacherAction
{

    public function execute($id)
    {

        $teacher = Teacher::findOrFail($id);
        $name = $teacher->name;

        if ($teacher->image != "Images/Admins/teacherDefault.png" && file_exists(public_path($teacher->image))) {
            unlink(public_path($teacher->image));
        }

        $admin = Admin::where('teacher_id', $teacher->id)->first();
        $admin->delete();
        $teacher->delete();

        $data = ['element' => 'teacher', 'name' => $name];
        session(['delete_info' => $data]);
        session(['link' => '/teachers']);
        return redirect()->route('delete.confirmation');
    }
}