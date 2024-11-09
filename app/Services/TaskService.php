<?php

namespace App\Services;

use Exception;
use App\Models\Task;
use App\Models\TaskHistory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Services\Task\DTOs\TaskDTO;
use App\Services\Task\DTOs\UpdateTaskDTO;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskService
{


    public function getTaskById(string $id): Task
    {
        return Task::with('author', 'category')->findOrFail($id);
    }




    public function getTaskById2(string $id): ?Task
    {
        return Task::with('author', 'category')->find($id);
    }




    public function storeTask(TaskDTO $taskDTO): Task
    {
        try {
            return DB::transaction(function () use ($taskDTO) {
                $task = Task::create([
                    'title' => $taskDTO->title,
                    'description' => $taskDTO->description,
                    'status' => $taskDTO->status,
                    'category_id' => $taskDTO->category_id,
                    'author_id' => $taskDTO->author_id,
                    'created_by' => $taskDTO->created_by,
                ]);

                TaskHistory::create([
                    'task_id' => $task->id,
                    'history_created_by' => $taskDTO->created_by,
                    'action' => 'Created',
                    'description' => $taskDTO->description,
                ]);

                return $task;
            });
        } catch (Exception $e) {
            throw new Exception('Failed to create task. Please try again.');
        }
    }





    public function storeTask1(array $taskData): Task
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




    public function storeTask0(array $taskData): Task
    {
        try {
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
        } catch (Exception $e) {
            //throw new Exception('Failed to create task. Please try again.');
            throw new Exception($e->getMessage());
        }
    }





    public function getTasksByRole(string $role, int $authorId): Collection
    {
        return Task::with('author', 'category')
            ->when($role !== 'admin', function ($query) use ($authorId) {
                $query->where('author_id', $authorId);
            })
            ->get();
    }





    public function getTasksByRole2(string $role, int $authorId)
    {
        return Task::with('author', 'category')
            ->when($role !== 'admin', function ($query) use ($authorId) {
                $query->where('author_id', $authorId);
            });
    }






    public function updateTask(Task $task, UpdateTaskDTO $taskDTO): Task
    {
        try {
            return DB::transaction(function () use ($task, $taskDTO) {
                // Update the task details
                $task->update([
                    'title' => $taskDTO->title,
                    'description' => $taskDTO->description,
                    'status' => $taskDTO->status,
                    'category_id' => $taskDTO->category_id,
                ]);

                // Log the update in task history
                TaskHistory::create([
                    'task_id' => $task->id,
                    'history_created_by' => $taskDTO->updated_by,
                    'action' => 'Updated',
                    'description' => $taskDTO->description,
                ]);

                return $task;
            });
        } catch (Exception $e) {
            throw new Exception('Failed to update task. Please try again.');
        }
    }





    public function deleteTask(Task $task): array
    {
        try {
            $task->delete();

            return [
                'success' => true,
                'message' => 'Task deleted successfully.'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete the task. Please try again.'
            ];
        }
    }
}
