<?php

namespace App\Actions\Task;

use App\Services\TaskService;

class StoreTaskAction
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

   
    
    public function execute(array $taskData)
    {
        return $this->taskService->storeTask($taskData);
    }
}
