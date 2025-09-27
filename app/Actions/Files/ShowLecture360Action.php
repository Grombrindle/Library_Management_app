<?php

namespace App\Actions\Files;

use App\Services\Files\FileService;
use Symfony\Component\HttpFoundation\Response;

class ShowLecture360Action
{
    public function execute(int $id): Response
    {
        return app(FileService::class)->getLecture360($id);
    }
}