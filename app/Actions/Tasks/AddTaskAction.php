<?php

namespace App\Actions\Tasks;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class AddTaskAction {

    public function execute(Request $request) {
        return Task::create([
            'title'          => $request['title'] ?? null,
            'description'    => $request['description'] ?? null,
            'estimatedHours' => $request['estimatedHours'] ?? null,
            'user_id'        => Auth::id(),
        ]);
    }
}