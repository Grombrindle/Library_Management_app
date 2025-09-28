<?php

namespace App\Actions\Resources;

use App\Models\Resource;

class AddResourceAction
{
    public function execute(array $data): array
    {
        $resource = Resource::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'file_path' => $data['file_path'] ?? null,
            'course_id' => $data['course_id'] ?? null,
        ]);

        return [
            'success' => true,
            'resource' => $resource,
        ];
    }
}