<?php

namespace App\Actions\Exams;

use App\Models\Exam;

class DeleteExamAction
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

    public function execute(int $id)
    {
        try {
            $exam = Exam::find($id);
            if (!$exam) {
                return response()->json(['success' => false, 'message' => 'Exam not found'], 404);
            }

            $name = $exam->title;

            if ($exam->thumbnailUrl && file_exists(public_path($exam->thumbnailUrl)) && $exam->thumbnailUrl !== 'Images/Exams/default.png') {
                unlink(public_path($exam->thumbnailUrl));
            }
            if ($exam->pdf && file_exists(public_path($exam->pdf)) && $exam->pdf !== 'Files/Exams/default.pdf') {
                unlink(public_path($exam->pdf));
            }

            $exam->delete();

            session(['delete_info' => ['element' => 'exam', 'name' => $name]]);
            session(['link' => '/exams']);
            return redirect()->route('delete.confirmation');

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete exam', 'error' => $e->getMessage()], 500);
        }
    }
}
