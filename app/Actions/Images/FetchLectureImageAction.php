<?php

namespace App\Actions\Images;

use App\Models\Lecture;

class FetchLectureImageAction
{
    public function execute(int $id)
    {
        $lecture = Lecture::find($id);

        if (!$lecture) {
            return ['success' => false, 'reason' => 'Lecture Not Found', 'status' => 404];
        }

        $filePath = public_path($lecture->image);

        if (!$lecture->image || !file_exists($filePath)) {
            return ['success' => false, 'reason' => 'Image Not Found', 'status' => 404];
        }

        return ['success' => true, 'filePath' => $filePath];
    }
}