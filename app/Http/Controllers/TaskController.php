<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskController extends Controller
{
    //
    public function fetchAll() {
        return response()->json([
            'success' => true,
            'tasks' => Auth::user()->tasks()->get()
        ]);
    }

    public function add(Request $request) {
        $task = Task::create([
            'text' => $request->input('text'),
            'user_id' => Auth::id(),
        ]);
        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }

    public function edit(Request $request, $id) {
        $task = Task::findOrFail($id);
        $task->update([
            'text' => $request->input('text'),
        ]);
        return response()->json([
            'success' => true,
            'task' => Task::findOrFail($id)
        ]);
    }

    public function toggleChecked($id) {
        $task = Task::findOrFail($id);
        $task->isChecked ^= true;
        $task->save();
        return response()->json([
            'success' => true,
            'task' => Task::findOrFail($id)
        ]);
    }

    public function toggleDelete($id) {
        $task = Task::findOrFail($id);
        $task->isTrashed ^= true;
        $task->trashed_at = $task->trashed_at ? null : now();
        $task->save();
        return response()->json([
            'success' => true,
            'task' => Task::findOrFail($id)
        ]);
    }

    public function delete($id) {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json([
            'success' => true,
            'tasks' => Auth::user()->tasks()->get()
        ]);
    }
}
