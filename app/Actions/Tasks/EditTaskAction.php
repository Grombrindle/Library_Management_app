<?php

namespace App\Actions\Tasks;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class EditTaskAction {

    public function execute(Request $request, $id) {
        $task = Task::findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            return null;
        }
        $task->update([
            'title'          => $request['title'] ?? $task->title,
            'description'    => $request['description'] ?? $task->description,
            'estimatedHours' => $request['estimatedHours'] ?? $task->estimatedHours,
        ]);
        return $task->fresh();
    }
}