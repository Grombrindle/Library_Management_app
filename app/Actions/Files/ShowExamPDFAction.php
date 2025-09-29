<?php

namespace App\Actions\Files;

use App\Services\FileService;
use Symfony\Component\HttpFoundation\Response;

class ShowExamPDFAction
{
    public function execute(int $id): Response
    {
        return app(FileService::class)->getExamPDF($id);
    }
}
