<?php

namespace App\Actions\Exams;

use Illuminate\Http\Request;
use App\Services\Exams\ExamService;

class EditExamAction
{
    public function execute(Request $request, int $id)
    {
        return app(ExamService::class)->editExam($request, $id);
    }
}