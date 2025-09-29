<?php

namespace App\Actions\Files;

use App\Services\FileService;
use Symfony\Component\HttpFoundation\Response;

class ShowResourceAudioAction
{
    public function execute(int $id): Response
    {
        return app(FileService::class)->getResourceAudio($id);
    }
}
