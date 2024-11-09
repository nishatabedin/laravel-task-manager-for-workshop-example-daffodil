<?php

namespace App\Services\Task\DTOs;

class TaskDTO
{
    public string $title;
    public string $description;
    public string $status;
    public int $category_id;
    public int $author_id;
    public int $created_by;

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->status = $data['status'];
        $this->category_id = $data['category_id'];
        $this->author_id = $data['author_id'];
        $this->created_by = $data['created_by'];
    }
}
