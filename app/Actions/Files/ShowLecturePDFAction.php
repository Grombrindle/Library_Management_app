<?php

namespace App\Actions\Files;

use App\Services\Files\FileService;
use Symfony\Component\HttpFoundation\Response;

class ShowLecturePDFAction
{
    public function execute(int $id): Response
    {
        return app(FileService::class)->getLecturePDF($id);
    }
}