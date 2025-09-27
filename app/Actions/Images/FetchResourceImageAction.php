<?php

namespace App\Actions\Images;

use App\Models\Resource;

class FetchResourceImageAction
{
    public function execute(int $id)
    {
        $resource = Resource::find($id);

        if (!$resource) {
            return ['success' => false, 'reason' => 'Resource Not Found', 'status' => 404];
        }

        $filePath = public_path($resource->image);

        if (!$resource->image || !file_exists($filePath)) {
            return ['success' => false, 'reason' => 'Image Not Found', 'status' => 404];
        }

        return ['success' => true, 'filePath' => $filePath];
    }
}