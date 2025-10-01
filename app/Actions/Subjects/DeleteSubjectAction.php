<?php

namespace App\Actions\Subjects;

use App\Models\Subject;

class DeleteSubjectAction
{

    public function execute($id)
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