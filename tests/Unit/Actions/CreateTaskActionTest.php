<?php

use App\Actions\CreateTaskAction;
use App\Models\Task;
use App\Repository\Eloquent\EloquentRepository;
use App\Repository\Repository;

it('should be possible create a task', function () {
    $task = Task::factory()->make()->toArray();
    $repository = new Repository(new EloquentRepository);
    $action = new CreateTaskAction($repository);

    $action->handle($task);

    $this->assertDatabaseHas('tasks', [
        'title' => $task['title'],
        'description' => $task['description'],
        'user_id' => $task['user_id'],
        'category_id' => $task['category_id'],
        'completed' => $task['completed'],
    ]);
});
