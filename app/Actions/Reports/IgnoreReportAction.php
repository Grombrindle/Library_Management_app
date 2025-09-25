<?php

namespace App\Actions\Reports;

use App\Models\Report;
use App\Services\Reports\ReportModerationService;

class IgnoreReportAction
{
    public function __construct(private ReportModerationService $service) {}

    public function __invoke(Report $report): void
    {
        $this->service->markIgnored($report);
    }
}


