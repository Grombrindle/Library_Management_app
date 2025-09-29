<?php

namespace App\Actions\Files;

use App\Services\FileService;
use Symfony\Component\HttpFoundation\Response;

class ShowLecture1080Action
{
    public function execute(int $id): Response
    {
        return app(FileService::class)->getLecture1080($id);
    }
}
