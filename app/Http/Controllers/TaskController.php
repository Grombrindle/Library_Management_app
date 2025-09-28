<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Tasks\TaskService;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function fetchAll()
    {
        return response()->json([
            'success' => true,
            'tasks' => $this->taskService->fetchAll()
        ]);
    }

    public function add(Request $request)
    {
        $task = $this->taskService->add($request->all());
        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }

    public function edit(Request $request, $id)
    {
        $task = $this->taskService->edit($id, $request->all());
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        return response()->json(['success' => true, 'task' => $task]);
    }

    public function toggleChecked($id)
    {
        $task = $this->taskService->toggleChecked($id);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        return response()->json(['success' => true, 'task' => $task]);
    }

    public function toggleDelete($id)
    {
        $task = $this->taskService->toggleDelete($id);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        return response()->json(['success' => true, 'task' => $task]);
    }

    public function restore($id)
    {
        $task = $this->taskService->restore($id);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        return response()->json(['success' => true, 'task' => $task]);
    }

    public function delete($id)
    {
        $tasks = $this->taskService->delete($id);
        if (!$tasks) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        return response()->json(['success' => true, 'tasks' => $tasks]);
    }

    public function trashedTasks()
    {
        return response()->json([
            'status' => 'success',
            'tasks' => $this->taskService->trashedTasks()
        ]);
    }

    public function availableTasks()
    {
        return response()->json([
            'status' => 'success',
            'tasks' => $this->taskService->availableTasks()
        ]);
    }
}