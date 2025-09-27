<?php

namespace App\Actions\Exams;

use Illuminate\Http\Request;
use App\Services\Exams\ExamService;

class AddExamAction
{
    public function execute(Request $request)
    {
        return app(ExamService::class)->addExam($request);
    }
}