<?php

namespace App\Actions;

use App\Interface\CreateTaskActionInterface;
use App\Models\Task;

class CreateTaskAction implements CreateTaskActionInterface
{
    public function handle(array $data): Task
    {

        return Task::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'due_date' => $data['due_date'] ?? null,
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'],
            'completed' => $data['completed'],
        ]);
    }
}
