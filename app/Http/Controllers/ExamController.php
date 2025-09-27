<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Actions\Exams\{
    AddExamAction,
    DeleteExamAction,
    EditExamAction,
    FetchAllExamsAction,
    FetchExamAction,
    FetchExamsFromSubjectAction,
    FetchExamsFromYearAction
};
class ExamController extends Controller
{
    // protected ExamService $examService;

    // public function __construct(ExamService $examService)
    // {
    //     $this->examService = $examService;
    // }

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

    // Fetch a single exam
    public function fetch(int $id)
    {
        return app(FetchExamAction::class)->execute($id);
    }

    // Fetch all exams
    public function fetchAll(Request $request)
    {
        return app(FetchAllExamsAction::class)->execute($request);
    }

    // Fetch exams from a specific subject
    public function fetchFromSubject(Request $request, int $subjectId)
    {
        return app(FetchExamsFromSubjectAction::class)->execute($request, $subjectId);
    }

    // Fetch exams from a specific year
    public function fetchFromYear(Request $request, int $year)
    {
        return app(FetchExamsFromYearAction::class)->execute($request, $year);
    }
}