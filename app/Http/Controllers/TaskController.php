<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskController extends Controller
{
    //
    public function fetchAll()
    {
        return response()->json([
            'success' => true,
            'tasks' => Auth::user()->tasks()->get()
        ]);
    }

    public function add(Request $request)
    {
        $task = Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'estimatedHours' => $request->input('estimatedHours'),
            'user_id' => Auth::id(),
        ]);
        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }

    public function edit(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        $task->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'estimatedHours' => $request->input('estimatedHours')
        ]);
        return response()->json([
            'success' => true,
            'task' => Task::findOrFail($id)
        ]);
    }

    public function toggleChecked($id)
    {
        $task = Task::findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        $task->isChecked ^= true;
        $task->save();
        return response()->json([
            'success' => true,
            'task' => Task::findOrFail($id)
        ]);
    }

    public function toggleDelete($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        if ($task->trashed()) {
            $task->restore();
        } else {
            $task->delete();
        }
        return response()->json([
            'success' => true,
            'task' => Task::withTrashed()->findOrFail($id)
        ]);
    }

    public function restore($id)
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        $task->restore();
        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }

    public function delete($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        $task->delete();
        return response()->json([
            'success' => true,
            'tasks' => Auth::user()->tasks()->get()
        ]);
    }

    /**
     * Get all soft-deleted tasks for the authenticated user.
     */
    public function trashedTasks(Request $request)
    {
        $user = $request->user();

        $tasks = Task::onlyTrashed()
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
            'status' => 'success',
            'tasks' => $tasks,
        ]);
    }

    /**
     * Get all available (non-deleted) tasks for the authenticated user.
     */
    public function availableTasks(Request $request)
    {
        $user = $request->user();

        $tasks = Task::where('user_id', $user->id)->get();

        return response()->json([
            'status' => 'success',
            'tasks' => $tasks,
        ]);
    }
}
