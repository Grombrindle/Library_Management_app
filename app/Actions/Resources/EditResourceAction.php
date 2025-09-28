<?php

namespace App\Actions\Resources;

use App\Models\Resource;

class EditResourceAction
{
    public function execute(int $id, array $data): array
    {
        $resource = Resource::find($id);

        if (!$resource) {
            return [
                'success' => false,
                'reason' => 'Resource not found',
            ];
        }

        $resource->update([
            'title' => $data['title'] ?? $resource->title,
            'description' => $data['description'] ?? $resource->description,
            'file_path' => $data['file_path'] ?? $resource->file_path,
            'course_id' => $data['course_id'] ?? $resource->course_id,
        ]);

        return [
            'success' => true,
            'resource' => $resource,
        ];
    }
}