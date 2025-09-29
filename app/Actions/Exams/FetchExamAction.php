<?php

namespace App\Actions\Exams;

use App\Services\ExamService;

class FetchExamAction
{
    public function execute(int $id)
    {
        return app(ExamService::class)->fetchExam($id);
    }
}
