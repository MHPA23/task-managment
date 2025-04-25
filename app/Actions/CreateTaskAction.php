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
            'completed' => $data['completed'],
        ]);
    }
}
