<?php

use App\Models\Task;

it('should be possible create a task', function () {
    $task = Task::factory()->make()->toArray();
    $action = (new \App\Actions\CreateTaskAction);

    $action->handle($task);

    $this->assertDatabaseHas('tasks', [
        'title' => $task['title'],
        'description' => $task['description'],
        'user_id' => $task['user_id'],
        'category_id' => $task['category_id'],
        'completed' => $task['completed'],
    ]);
});
