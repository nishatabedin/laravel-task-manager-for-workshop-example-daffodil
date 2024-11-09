<?php

namespace App\Services\Task\DTOs;

class UpdateTaskDTO
{
    public string $title;
    public string $description;
    public string $status;
    public int $category_id;
    public int $updated_by;

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->status = $data['status'];
        $this->category_id = $data['category_id'];
        $this->updated_by = $data['updated_by'];
    }
}
