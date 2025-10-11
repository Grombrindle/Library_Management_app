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
        if ($task->trashed()) {
            $task->restore();
            $task->isTrashed = false;
        } else {
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
        $task->isTrashed = 0;
        $task->save();

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
