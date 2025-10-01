<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\ExamService;

use App\Actions\Exams\{
    AddExamAction,
    DeleteExamAction,
    EditExamAction,
};
class ExamController extends Controller
{
    // protected ExamService $examService;

    // public function __construct(ExamService $examService)
    // {
    //     $this->examService = $examService;
    // }


    // Fetch a single exam
    public function fetch(int $id)
    {
        return app(ExamService::class)->fetchExam($id);
    }

    // Fetch all exams
    public function fetchAll(Request $request)
    {
        return app(ExamService::class)->fetchAllExams($request);
    }

    // Fetch exams from a specific subject
    public function fetchFromSubject(Request $request, int $subjectId)
    {
        return app(ExamService::class)->fetchExamsFromSubject($request, $subjectId);
    }

    // Fetch exams from a specific year
    public function fetchFromYear(Request $request, int $year)
    {
        return app(ExamService::class)->fetchExamsFromYear($request, $year);
    }

    // Add a new exam
    public function add(Request $request)
    {
        return app(AddExamAction::class)->execute($request);
    }

    // Edit an existing exam
    public function edit(Request $request, int $id)
    {
        return app(EditExamAction::class)->execute($request, $id);
    }

    // Delete an exam
    public function delete(int $id)
    {
        return app(DeleteExamAction::class)->execute($id);
    }
}