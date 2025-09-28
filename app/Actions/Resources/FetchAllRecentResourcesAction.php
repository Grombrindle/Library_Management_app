<?php

namespace App\Actions\Resources;

use App\Models\Resource;

class FetchAllRecentResourcesAction
{
    public function execute()
    {
        return Resource::orderByDesc('created_at')->get();
    }
}