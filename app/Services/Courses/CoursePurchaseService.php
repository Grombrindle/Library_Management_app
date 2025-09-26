<?php

namespace App\Services\Courses;

class CoursePurchaseService
{
    public function purchaseCourse($user, $courseId)
    {
        $course = \App\Models\Course::find($courseId);
        if (!$course) {
            return response()->json(['success' => false, 'message' => 'Course not found'], 404);
        }

        if ($user->courses()->where('course_id', $courseId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Already subscribed'], 400);
        }

        if (!$course->sparkies) {
            return response()->json(['success' => false, 'message' => 'Course is not purchasable with sparkies'], 400);
        }

        $sparkiesPrice = (int)$course->sparkiesPrice;
        if ($user->sparkies < $sparkiesPrice) {
            return response()->json(['success' => false, 'message' => 'Insufficient Sparkies'], 400);
        }

        $user->sparkies -= $sparkiesPrice;
        $user->save();
        $user->courses()->attach($courseId);

        return response()->json(['success' => true, 'message' => 'Course purchased successfully']);
    }
}