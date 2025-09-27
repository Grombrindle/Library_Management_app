<?php

namespace App\Services\Reports;

use App\Actions\Reports\AddReportAction;
use App\Actions\Reports\IgnoreReportAction;
use App\Actions\Reports\WarnReportAction;
use App\Actions\Reports\TestReportAction;

class ReportService
{
    protected $addReportAction;
    protected $ignoreReportAction;
    protected $warnReportAction;
    protected $testReportAction;

    public function __construct(
        AddReportAction $addReportAction,
        IgnoreReportAction $ignoreReportAction,
        WarnReportAction $warnReportAction
    ) {
        $this->addReportAction = $addReportAction;
        $this->ignoreReportAction = $ignoreReportAction;
        $this->warnReportAction = $warnReportAction;
    }

    public function addReport(array $data)
    {
        return $this->addReportAction->execute($data);
    }

    public function ignoreReport(int $reportId): ?array
    {
        return $this->ignoreReportAction->execute($reportId);
    }

    public function warnReport(int $reportId): ?array
    {
        return $this->warnReportAction->execute($reportId);
    }
}