<?php

namespace App\Actions\Reports;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class IgnoreReportAction
{
    public function execute(int $reportId): ?array
    {
        $report = Report::find($reportId);

        if (!$report) {
            return null;
        }

        $report->status = "IGNORED";
        $report->handled_by_id = Auth::id();
        $report->save();

        return ['id' => $report->id, 'status' => $report->status, 'user_id' => $report->user_id];
    }
}