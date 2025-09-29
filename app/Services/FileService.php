<?php

namespace App\Services;

use App\Models\Lecture;
use App\Models\Resource;
use App\Models\Exam;
use Symfony\Component\HttpFoundation\Response;

class FileService
{
    /**
     * Return 360p lecture video
     */
    public function getLecture360(int $id): Response
    {
        return $this->serveFile(Lecture::findOrFail($id)->file_360);
    }

    /**
     * Return 720p lecture video
     */
    public function getLecture720(int $id): Response
    {
        return $this->serveFile(Lecture::findOrFail($id)->file_720);
    }

    /**
     * Return 1080p lecture video
     */
    public function getLecture1080(int $id): Response
    {
        return $this->serveFile(Lecture::findOrFail($id)->file_1080);
    }

    /**
     * Return lecture PDF
     */
    public function getLecturePDF(int $id): Response
    {
        $lecture = Lecture::findOrFail($id);
        return $this->serveFile($lecture->file_pdf, 'application/pdf');
    }

    /**
     * Return resource PDF by language
     */
    public function getResourcePDF(int $id, ?string $language = null): Response
    {
        $resource = Resource::findOrFail($id);

        $pdfFiles = $resource->pdf_files;
        $language ??= array_key_first(array_filter($pdfFiles));

        if (!$language || !isset($pdfFiles[$language]) || !$pdfFiles[$language]) {
            abort(404, 'PDF not found for the selected language.');
        }

        return $this->serveFile($pdfFiles[$language], 'application/pdf');
    }

    /**
     * Return resource audio
     */
    public function getResourceAudio(int $id): Response
    {
        $resource = Resource::findOrFail($id);
        return $this->serveFile($resource->audio_file);
    }

    /**
     * Return exam PDF
     */
    public function getExamPDF(int $id): Response
    {
        $exam = Exam::findOrFail($id);
        return $this->serveFile($exam->pdf, 'application/pdf');
    }

    /**
     * Serve a file from public directory
     */
    private function serveFile(?string $path, ?string $contentType = null): Response
    {
        if (!$path || !file_exists(public_path($path))) {
            abort(404, 'File not found on server.');
        }

        $contentType ??= mime_content_type(public_path($path));

        return response()->file(public_path($path), [
            'Content-Type' => $contentType,
        ]);
    }
}
