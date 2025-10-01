<?php

namespace App\Services;

use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SubjectService
{
    public function fetch($id)
    {
        $subject = Subject::find($id);


        if ($subject) {
            return [
                'success' => "true",
                'subject' => $subject,
                'status' => 200
            ];
        }

        return [
            'success' => "false",
            'reason' => "Subject Not Found",
            'status' => 404
        ];
    }

    public function fetchTeachers($id)
    {
        $subject = Subject::find($id);

        if ($subject) {
            $teachers = $subject->teachers;

            $teachers->each(function ($teacher) {
                $teacher->isFavorite = Auth::user()->favoriteTeachers()
                    ->where('teacher_id', $teacher->id)
                    ->exists();
            });

            return [
                'success' => "true",
                'teachers' => $teachers,
                'status' => 200
            ];
        }

        return [
            'success' => "false",
            'reason' => "Subject Not Found",
            'status' => 404
        ];
    }

    public function fetchAll()
    {
        return [
            'success' => true,
            'subjects' => Subject::all(),
            'status' => 200
        ];
    }

    public function fetchScientific()
    {
        return [
            'success' => true,
            'subjects' => Subject::where('literaryOrScientific', 1)->get(),
            'status' => 200
        ];
    }

    public function fetchLiterary()
    {
        return [
            'success' => true,
            'subjects' => Subject::where('literaryOrScientific', 0)->get(),
            'status' => 200
        ];
    }
}
