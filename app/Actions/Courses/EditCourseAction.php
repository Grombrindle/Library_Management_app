<?php

namespace App\Actions\Courses;

use App\Models\Course;

class EditCourseAction
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

    public function execute($courseId, array $data, $file = null)
    {
        $course = Course::findOrFail($courseId);

        if ($file) {
            $path = $this->storeImage($file, 'Images/Courses');
            if ($course->image != "Images/Courses/default.png" && file_exists(public_path($course->image))) {
                unlink(public_path($course->image));
            }
            $course->image = $path;
        }

        $sources = $this->decodeJson($data['sources'] ?? []);
        $sparkiesPrice = $this->calculateSparkiesPrice($data['course_paid'] ?? false, $data['course_price'] ?? 0);

        $course->update([
            'name' => $data['course_name'],
            'description' => $data['course_description'] ?? null,
            'price' => $data['course_price'] ?? 0,
            'sparkies' => $data['course_paid'] ?? false,
        ]);

        // Default fallback: redirect with info
        $dataSession = ['element' => 'course', 'id' => $data['id'] ?? null, 'name' => $data['course_name']];
        session(['update_info' => $dataSession]);
        session(['link' => '/courses']);
        return redirect()->route('update.confirmation');
    }
}