<?php

namespace App\Actions\Files;

use App\Services\Files\FileService;
use Symfony\Component\HttpFoundation\Response;

class ShowExamPDFAction
{
    public function execute(int $id): Response
    {
        return app(FileService::class)->getExamPDF($id);
    }
}