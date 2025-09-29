<?php

namespace App\Actions\Exams;

use Illuminate\Http\Request;
use App\Services\ExamService;

class FetchAllExamsAction
{
    public function execute(Request $request)
    {
        return app(ExamService::class)->fetchAllExams($request);
    }
}
