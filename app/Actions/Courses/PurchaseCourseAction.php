<?php
namespace App\Actions\Courses;

use App\Models\Course;
use App\Models\Subscription;

class PurchaseCourseAction
{
    public function execute($user, $courseId)
    {
        // Check if the course exists
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(['success' => false, 'message' => 'Course not found'], 404);
        }

        // Check if the user is already subscribed to the course
        if ($user->courses()->where('course_id', $courseId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Already subscribed'], 400);
        }

        // Check if the course is purchasable with sparkies
        if (!$course->sparkies) {
            return response()->json(['success' => false, 'message' => 'Course is not purchasable with sparkies'], 400);
        }

        // Check if the user has enough sparkies
        $sparkiesPrice = (int) $course->sparkiesPrice;
        if ($user->sparkies < $sparkiesPrice) {
            return response()->json(['success' => false, 'message' => 'Insufficient Sparkies'], 400);
        }

        // Deduct the sparkies from the user's account
        $user->sparkies -= $sparkiesPrice;
        $user->save();

        // Create a new subscription for the course
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'course_id' => $courseId,
        ]);

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Course purchased successfully',
            'subscription' => $subscription,
        ]);
    }
}