<?php

namespace App\Http\Controllers;

use App\Services\FileService;

class FileController extends Controller
{
    // Lecture files
    public function show360($id)
    {
        return app(FileService::class)->getLecture360($id);
    }

    public function show720($id)
    {
        return app(FileService::class)->getLecture720($id);
    }

    public function show1080($id)
    {
        return app(FileService::class)->getLecture1080($id);
    }

    public function showPDF($id)
    {
        return app(FileService::class)->getLecturePDF($id);
    }

    // Resource files
    public function showResourcePDF($id, $language = null)
    {
        return app(FileService::class)->getResourcePDF($id, $language);
    }

    public function showResourceAudio($id)
    {
        return app(FileService::class)->getResourceAudio($id);
    }

    // Exam files
    public function showExam($id)
    {
        return app(FileService::class)->getExamPDF($id);
    }
}