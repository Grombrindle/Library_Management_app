<?php

namespace App\Actions\Images;

use App\Models\Course;

class FetchCourseImageAction
{
    public function execute(int $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return ['success' => false, 'reason' => 'Course Not Found', 'status' => 404];
        }

        $filePath = public_path($course->image);

        if (!$course->image || !file_exists($filePath)) {
            return ['success' => false, 'reason' => 'Image Not Found', 'status' => 404];
        }

        return ['success' => true, 'filePath' => $filePath];
    }
}