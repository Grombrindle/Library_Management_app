<?php

namespace App\Actions\Resources;

use App\Models\Resource;

class FetchAllResourcesAction
{
    public function execute()
    {
        return Resource::all();
    }
}