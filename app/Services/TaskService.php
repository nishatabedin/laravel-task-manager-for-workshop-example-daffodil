<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskHistory;
use Illuminate\Support\Facades\DB;
use Exception;

class TaskService
{
    /**
     * Store a new task and its history.
     *
     * @param array $taskData
     * @return Task
     * @throws Exception
     */
    public function storeTask(array $taskData): Task
    {
        try {
            // Use a database transaction to ensure atomicity
            return DB::transaction(function () use ($taskData) {
                // Create the task
                $task = Task::create([
                    'title' => $taskData['title'],
                    'description' => $taskData['description'],
                    'status' => $taskData['status'],
                    'category_id' => $taskData['category_id'],
                    'author_id' => $taskData['author_id'],
                    'created_by' => $taskData['created_by'],
                ]);

                // Create a task history entry
                TaskHistory::create([
                    'task_id' => $task->id,
                    'history_created_by' => $taskData['created_by'],
                    'action' => 'Created',
                    'description' => $taskData['description'],
                ]);

                return $task;
            });
        } catch (Exception $e) {
            throw new Exception('Failed to create task. Please try again.');
        }
    }
}
