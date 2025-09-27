<?php

namespace App\Actions\Images;

use App\Models\Exam;

class FetchExamImageAction
{
    public function execute(int $id)
    {
        $exam = Exam::find($id);

        if (!$exam) {
            return ['success' => false, 'reason' => 'Exam Not Found', 'status' => 404];
        }

        $filePath = public_path($exam->thumbnailUrl);

        if (!$exam->thumbnailUrl || !file_exists($filePath)) {
            return ['success' => false, 'reason' => 'Image Not Found', 'status' => 404];
        }

        return ['success' => true, 'filePath' => $filePath];
    }
}