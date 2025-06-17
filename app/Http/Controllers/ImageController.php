<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Subject;
use App\Models\Resource;

class ImageController extends Controller
{
    //
    public function fetchTeacher($id)
    {
        $teacher = Teacher::find($id);
        if ($teacher) {
            $path = $teacher->image;
            $filePath = public_path($path);
            if (file_exists($filePath)) {
                $mimeType = mime_content_type($filePath);
                return response()->file($filePath, ['Content-Type' => $mimeType]);
            }
            return response()->json([
                'success' => 'false',
                'reason' => 'Image Not Found'
            ], 404);
        } else {
            return response()->json([
                'success' => 'false',
                'reason' => 'Teacher Not Found'
            ], 404);
        }
    }
    public function fetchLecture($id)
    {
        $lecture = Lecture::find($id);
        if ($lecture) {
            $path = $lecture->image;
            $filePath = public_path($path);
            if (file_exists($filePath)) {
                $mimeType = mime_content_type($filePath);
                return response()->file($filePath, ['Content-Type' => $mimeType]);
            }
            return response()->json([
                'success' => 'false',
                'reason' => 'Image Not Found'
            ], 404);
        } else {
            return response()->json([
                'success' => 'false',
                'reason' => 'Lecture Not Found'
            ], 404);
        }
    }
    public function fetchSubject($id)
    {
        $subject = Subject::find($id);
        if ($subject) {
            $path = $subject->image;
            $filePath = public_path($path);
            if (file_exists($filePath)) {
                $mimeType = mime_content_type($filePath);
                return response()->file($filePath, ['Content-Type' => $mimeType]);
            }
            return response()->json([
                'success' => 'false',
                'reason' => 'Image Not Found'
            ], 404);
        } else {
            return response()->json([
                'success' => 'false',
                'reason' => 'Subject Not Found'
            ], 404);
        }
    }

    public function fetchCourse($id)
    {
        $course = Course::find($id);
        if ($course) {
            $path = $course->image;
            $filePath = public_path($path);
            if (file_exists(filename: $filePath)) {
                $mimeType = mime_content_type($filePath);
                return response()->file($filePath, ['Content-Type' => $mimeType]);
            }
            return response()->json([
                'success' => 'false',
                'reason' => 'Image Not Found'
            ], 404);
        } else {
            return response()->json([
                'success' => 'false',
                'reason' => 'Course Not Found'
            ], 404);
        }
    }
    
    public function fetchResource($id)
    {
        $resource = Resource::find($id);
        if ($resource) {
            $path = $resource->image;
            $filePath = public_path($path);
            if (file_exists(filename: $filePath)) {
                $mimeType = mime_content_type($filePath);
                return response()->file($filePath, ['Content-Type' => $mimeType]);
            }
            return response()->json([
                'success' => 'false',
                'reason' => 'Image Not Found'
            ], 404);
        } else {
            return response()->json([
                'success' => 'false',
                'reason' => 'Resource Not Found'
            ], 404);
        }
    }

}