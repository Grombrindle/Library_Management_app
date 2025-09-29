<?php

namespace App\Actions\Exams;

use Illuminate\Http\Request;
use App\Services\ExamService;

class FetchExamsFromYearAction
{
    public function execute(Request $request, int $year)
    {
        return app(ExamService::class)->fetchExamsFromYear($request, $year);
    }
}
