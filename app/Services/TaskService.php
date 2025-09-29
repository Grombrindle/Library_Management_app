<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function fetchAll()
    {
        return Auth::user()->tasks()->get();
    }

    public function add(array $data)
    {
        return Task::create([
            'title'          => $data['title'] ?? null,
            'description'    => $data['description'] ?? null,
            'estimatedHours' => $data['estimatedHours'] ?? null,
            'user_id'        => Auth::id(),
        ]);
    }

    public function edit(int $id, array $data)
    {
        $task = Task::findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            return null;
        }
        $task->update([
            'title'          => $data['title'] ?? $task->title,
            'description'    => $data['description'] ?? $task->description,
            'estimatedHours' => $data['estimatedHours'] ?? $task->estimatedHours,
        ]);
        return $task->fresh();
    }

    public function toggleChecked(int $id)
    {
        $task = Task::findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            return null;
        }
        $task->isChecked = !$task->isChecked;
        $task->save();
        return $task->fresh();
    }

    public function toggleDelete(int $id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            return null;
        }
        if($task->trashed()) {
            $task->restore();
            $task->isTrashed = false;
        }
        else {
            $task->delete();
            $task->isTrashed = true;
        }
        $task->save();
        return Task::withTrashed()->findOrFail($id);
    }

    public function restore(int $id)
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            return null;
        }
        $task->restore();
        return $task;
    }

    public function delete(int $id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            return null;
        }
        $task->forceDelete();
        return Auth::user()->tasks()->get();
    }

    public function trashedTasks()
    {
        return Task::onlyTrashed()
            ->where('user_id', Auth::id())
            ->get();
    }

    public function availableTasks()
    {
        return Task::where('user_id', Auth::id())->get();
    }
}
