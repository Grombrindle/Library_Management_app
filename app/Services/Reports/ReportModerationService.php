<?php

namespace App\Services\Reports;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportModerationService
{
    public function markIgnored(Report $report): void
    {
        $report->status = 'IGNORED';
        $report->handled_by_id = Auth::id();
        $report->save();
    }

    public function markWarned(Report $report): void
    {
        $report->status = 'WARNED';
        $report->handled_by_id = Auth::id();
        $report->save();
    }
}


