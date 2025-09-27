<?php

namespace App\Actions\Images;

use App\Models\Teacher;

class FetchTeacherImageAction
{
    public function execute(int $id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return ['success' => false, 'reason' => 'Teacher Not Found', 'status' => 404];
        }

        $filePath = public_path($teacher->image);

        if (!$teacher->image || !file_exists($filePath)) {
            return ['success' => false, 'reason' => 'Image Not Found', 'status' => 404];
        }

        return ['success' => true, 'filePath' => $filePath];
    }
}