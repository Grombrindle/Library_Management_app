<?php

namespace App\Actions\Courses;

use App\Models\Course;

class AddCourseAction
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
    public function execute($user, array $data, $file = null)
    {
        $imagePath = $file ? $this->storeImage($file, 'Images/CourseRequests') : "Images/Courses/default.png";

        $sources = $this->decodeJson($data['sources'] ?? []);
        $requirements = $this->decodeJson($data['requirements'] ?? []);
        $sparkiesPrice = $this->calculateSparkiesPrice($data['course_paid'] ?? false, $data['course_price'] ?? 0);

        // If a teacher is selected, create the actual course
        if (!empty($data['teacher'])) {
            $course = Course::create([
                'name' => $data['course_name'],
                'teacher_id' => $data['teacher'],
                'subject_id' => $data['subject'] ?? null,
                'description' => $data['course_description'] ?? null,
                'lecturesCount' => 0,
                'subscriptions' => 0,
                'sources' => $sources ? json_encode($sources) : null,
                'price' => $data['course_price'] ?? 0,
                'sparkies' => $data['course_paid'] ?? false,
                'sparkiesPrice' => $sparkiesPrice,
                'requirements' => $requirements,
                'image' => $imagePath,
            ]);

            $dataSession = ['element' => 'course', 'id' => $course->id, 'name' => $course->name];
            session(['add_info' => $dataSession]);
            session(['link' => '/courses']);
            return redirect()->route('add.confirmation');
        }

        // If teacher is not provided, create a course request for approval
        if ($user->privileges === 0) { // Teacher user
            $courseRequestData = [
                'teacher_id' => $user->teacher_id,
                'name' => $data['course_name'],
                'description' => $data['course_description'] ?? null,
                'subject_id' => $data['subject'] ?? null,
                'image' => $imagePath,
                'sources' => $sources ? json_encode($sources) : null,
                'requirements' => $requirements,
                'price' => $data['course_price'] ?? null,
                'sparkies' => $data['course_paid'] ?? false,
                'sparkiesPrice' => $sparkiesPrice,
                'status' => 'pending',
                'admin_id' => null,
                'course_id' => $data['id'] ?? null,
                'rejection_reason' => null,
                'lecturesCount' => $data['lecturesCount'] ?? 0,
                'subscriptions' => $data['subscriptions'] ?? 0,
            ];
            \App\Models\CourseRequest::create($courseRequestData);

            $dataSession = ['element' => 'course', 'id' => $data['id'] ?? null, 'name' => $data['course_name']];
            session(['add_info' => $dataSession]);
            session(['link' => '/courses']);
            return redirect()->route('add.confirmation');
        }

        // Default fallback: redirect with info
        $dataSession = ['element' => 'course', 'id' => $data['id'] ?? null, 'name' => $data['course_name']];
        session(['add_info' => $dataSession]);
        session(['link' => '/courses']);
        return redirect()->route('add.confirmation');
    }
}