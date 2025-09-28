<?php

namespace App\Actions\Resources;

use App\Models\Resource;

class DeleteResourceAction
{
    public function execute(int $id): array
    {
        $resource = Resource::find($id);

        if (!$resource) {
            return [
                'success' => false,
                'reason' => 'Resource not found',
            ];
        }

        $resource->delete();

        return [
            'success' => true,
            'message' => 'Resource deleted successfully',
        ];
    }
}