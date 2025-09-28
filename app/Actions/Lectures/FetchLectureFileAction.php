<?php

namespace App\Actions\Lectures;

use App\Models\Lecture;

class FetchLectureFileAction
{
    public function execute(int $lectureId, string $fileType): array
    {
        $lecture = Lecture::find($lectureId);

        if (!$lecture) {
            return [
                'success' => false,
                'message' => 'Lecture not found'
            ];
        }

        $filePath = match ($fileType) {
            '360' => $lecture->file_360,
            '720' => $lecture->file_720,
            '1080' => $lecture->file_1080,
            'pdf' => $lecture->file_pdf,
            default => null
        };

        if (!$filePath) {
            return [
                'success' => false,
                'message' => "File of type {$fileType} not found"
            ];
        }

        return [
            'success' => true,
            'url' => url($filePath)
        ];
    }
}