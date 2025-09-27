<?php

namespace App\Actions\Images;

use App\Models\Subject;

class FetchSubjectImageAction
{
    public function execute(int $id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return ['success' => false, 'reason' => 'Subject Not Found', 'status' => 404];
        }

        $filePath = public_path($subject->image);

        if (!$subject->image || !file_exists($filePath)) {
            return ['success' => false, 'reason' => 'Image Not Found', 'status' => 404];
        }

        return ['success' => true, 'filePath' => $filePath];
    }
}