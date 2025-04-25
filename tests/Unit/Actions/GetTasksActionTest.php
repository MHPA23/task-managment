<?php

use App\Models\Task;

it('should be possible to get tasks by date range', function () {

    Task::factory()->create([
        'created_at' => '2020-01-01 00:00:00',
        'updated_at' => '2021-01-01 00:00:00',
    ]);

    Task::factory()->create([
        'created_at' => '2023-01-01 00:00:00',
        'updated_at' => '2023-01-01 00:00:00',
    ]);

    $dateInit = '2023-01-01';
    $dateEnd = '2023-12-31';

    $action = (new \App\Actions\GetTasksAction);
    $tasks = $action->handle([
        'date_init' => $dateInit,
        'date_end' => $dateEnd,
    ]);

    expect($tasks)->toHaveCount(1);
    expect($tasks[0]->created_at)->toEqual('2023-01-01 00:00:00');
});

it('should be possible to get tasks by completed', function () {

    Task::factory()->create([
        'completed' => true,
    ]);

    Task::factory()->create([
        'completed' => false,
    ]);

    $action = (new \App\Actions\GetTasksAction);
    $tasks = $action->handle([
        'completed' => true,
    ]);

    expect($tasks)->toHaveCount(1);
});

it('should be possible to get tasks by title', function () {

    Task::factory()->create([
        'title' => 'Test task',
    ]);

    Task::factory()->create([
        'title' => 'Another task',
    ]);

    $action = (new \App\Actions\GetTasksAction);
    $tasks = $action->handle([
        'title' => 'Test',
    ]);

    expect($tasks)->toHaveCount(1);
});

it('should be possible to get tasks by sort_by', function () {

    Task::factory()->create([
        'title' => 'Test task',
        'created_at' => '2023-01-01 00:00:00',
    ]);

    Task::factory()->create([
        'title' => 'Another task',
        'created_at' => '2023-01-02 00:00:00',
    ]);

    $action = (new \App\Actions\GetTasksAction);
    $tasks = $action->handle([
        'sort_by' => 'created_at',
        'sort_dir' => 'desc',
    ]);

    expect($tasks[0]->created_at)->toEqual('2023-01-02 00:00:00');
});

it('should be return results paginated', function () {

    Task::factory()->count(20)->create();

    $action = (new \App\Actions\GetTasksAction);
    $tasks = $action->handle();

    expect($tasks)->toHaveCount(10);
    expect($tasks)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
});
