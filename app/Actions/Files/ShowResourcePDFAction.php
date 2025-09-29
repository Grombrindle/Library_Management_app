<?php

namespace App\Actions\Files;

use App\Services\FileService;
use Symfony\Component\HttpFoundation\Response;

class ShowResourcePDFAction
{
    public function execute(int $id, ?string $language = null): Response
    {
        return app(FileService::class)->getResourcePDF($id, $language);
    }
}
