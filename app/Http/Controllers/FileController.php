<?php

namespace App\Http\Controllers;

use App\Actions\Files\{
    ShowExamPDFAction,
    ShowLecture360Action,
    ShowLecture720Action,
    ShowLecture1080Action,
    ShowLecturePDFAction,
    ShowResourcePDFAction,
    ShowResourceAudioAction,

};
class FileController extends Controller
{
    // Lecture files
    public function show360($id)
    {
        return app(ShowLecture360Action::class)->execute($id);
    }

    public function show720($id)
    {
        return app(ShowLecture720Action::class)->execute($id);
    }

    public function show1080($id)
    {
        return app(ShowLecture1080Action::class)->execute($id);
    }

    public function showPDF($id)
    {
        return app(ShowLecturePDFAction::class)->execute($id);
    }

    // Resource files
    public function showResourcePDF($id, $language = null)
    {
        return app(ShowResourcePDFAction::class)->execute($id, $language);
    }

    public function showResourceAudio($id)
    {
        return app(ShowResourceAudioAction::class)->execute($id);
    }

    // Exam files
    public function showExam($id)
    {
        return app(ShowExamPDFAction::class)->execute($id);
    }
}