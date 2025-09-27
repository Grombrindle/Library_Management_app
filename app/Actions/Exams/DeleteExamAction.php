<?php

namespace App\Actions\Exams;

use App\Services\Exams\ExamService;

class DeleteExamAction
{
    public function execute(int $id)
    {
        return app(ExamService::class)->deleteExam($id);
    }
}