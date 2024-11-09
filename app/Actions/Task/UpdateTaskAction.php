<?php

namespace App\Actions\Task;

use App\Models\Task;
use App\Services\TaskService;
use App\Services\Task\DTOs\UpdateTaskDTO;

class UpdateTaskAction
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }


    public function execute(Task $task, UpdateTaskDTO $taskDTO)
    {
        $this->taskService->updateTask($task, $taskDTO);
    }
}
