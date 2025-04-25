<?php

namespace App\Actions;

use App\Models\Task;

class CreateTaskAction
{
    public function handle(array $data): Task
    {

        return Task::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'due_date' => $data['due_date'] ?? null,
            'user_id' => $data['user_id'],
            'completed' => $data['completed'],
        ]);
    }
}
