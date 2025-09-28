<?php

namespace App\Actions\Resources;

use App\Models\Resource;

class FetchResourceAction
{
    public function execute(?int $id): ?Resource
    {
        if (!$id) {
            return null;
        }

        return Resource::find($id);
    }
}