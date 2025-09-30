<?php

namespace App\Actions\Exams;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Exam;

class EditExamAction
{

    private function ensureDirectoriesExist(): void
    {
        $imagesDir = public_path('Images/Exams');
        if (!is_dir($imagesDir)) {
            mkdir($imagesDir, 0755, true);
        }

        $filesDir = public_path('Files/Exams');
        if (!is_dir($filesDir)) {
            mkdir($filesDir, 0755, true);
        }
    }

    public function execute(Request $request, int $id)
    {
        $exam = Exam::find($id);

        if (!$exam) {
            return response()->json(['success' => false, 'message' => 'Exam not found'], 404);
        }

        $this->ensureDirectoriesExist();
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'object_image' => 'sometimes|file|mimes:jpeg,jpg,png,gif|max:2048',
            'pdf_file' => 'sometimes|file|mimes:pdf|max:10240',
            'subject_id' => 'sometimes|required|exists:subjects,id',
            'date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        try {
            if ($request->hasFile('object_image')) {
                if ($exam->thumbnailUrl && file_exists(public_path($exam->thumbnailUrl)) && $exam->thumbnailUrl !== 'Images/Exams/default.png') {
                    unlink(public_path($exam->thumbnailUrl));
                }
                $imageFile = $request->file('object_image');
                $imageFilename = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('Images/Exams'), $imageFilename);
                $exam->thumbnailUrl = 'Images/Exams/' . $imageFilename;
            }

            if ($request->has('title'))
                $exam->title = $request->title;
            if ($request->has('description'))
                $exam->description = $request->description;
            if ($request->has('subject_id'))
                $exam->subject_id = $request->subject_id;
            if ($request->has('date'))
                $exam->date = $request->date;

            $exam->save();

            session(['update_info' => ['element' => 'exam', 'id' => $id, 'name' => $exam->title]]);
            session(['link' => '/exams']);
            return redirect()->route('update.confirmation');

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update exam', 'error' => $e->getMessage()], 500);
        }
    }
}
