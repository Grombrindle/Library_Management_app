<?php

namespace App\Actions\Courses;

use App\Models\Course;

class DeleteCourseAction
{

    private function decodeJson($input)
    {
        if (is_string($input)) {
            $decoded = json_decode($input, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($input) ? $input : [];
    }

    private function calculateSparkiesPrice($isPaid, $price)
    {
        if (!$isPaid)
            return 0;
        if ($price <= 5)
            return 1;
        if ($price <= 10)
            return 2;
        return 3;
    }

    private function storeImage($file, $directory = 'Images/CourseRequests')
    {
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = public_path($directory);
        if (!file_exists($path))
            mkdir($path, 0755, true);
        $file->move($path, $filename);
        return $directory . '/' . $filename;
    }
    public function execute($courseId)
    {
        $course = Course::findOrFail($courseId);

        if ($course->image != "Images/Courses/default.png" && file_exists(public_path($course->image))) {
            unlink(public_path($course->image));
        }

        $course->delete();
        return redirect()->route('delete.confirmation');
    }
}