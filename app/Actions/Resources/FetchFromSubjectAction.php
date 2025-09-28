<?php

namespace App\Actions\Resources;

use App\Models\Resource;

class FetchFromSubjectAction
{
    public function execute(?int $subjectId)
    {
        if (!$subjectId) {
            return collect();
        }

        return Resource::where('subject_id', $subjectId)->get();
    }
}