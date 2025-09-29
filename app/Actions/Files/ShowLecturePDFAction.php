<?php

namespace App\Actions\Files;

use App\Services\FileService;
use Symfony\Component\HttpFoundation\Response;

class ShowLecturePDFAction
{
    public function execute(int $id): Response
    {
        return app(FileService::class)->getLecturePDF($id);
    }
}
