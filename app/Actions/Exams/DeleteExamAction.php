<?php

namespace App\Actions\Exams;

use App\Services\ExamService;

class DeleteExamAction
{
    public function execute(int $id)
    {
        return app(ExamService::class)->deleteExam($id);
    }
}
