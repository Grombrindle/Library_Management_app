<?php

namespace App\Actions\Exams;

use Illuminate\Http\Request;
use App\Services\ExamService;

class FetchExamsFromSubjectAction
{
    public function execute(Request $request, int $subjectId)
    {
        return app(ExamService::class)->fetchExamsFromSubject($request, $subjectId);
    }
}
