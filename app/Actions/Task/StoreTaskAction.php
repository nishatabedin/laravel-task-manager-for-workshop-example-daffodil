<?php

namespace App\Actions\Task;

use App\Services\TaskService;
use App\Services\Task\DTOs\TaskDTO;

class StoreTaskAction
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }



    public function execute(TaskDTO $taskDTO)
    {
        return $this->taskService->storeTask($taskDTO);
    }


    public function execute0(array $taskData)
    {
        return $this->taskService->storeTask0($taskData);
    }
}
