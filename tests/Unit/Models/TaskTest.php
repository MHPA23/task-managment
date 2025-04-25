<?php

use App\Models\Task;

test('task model has correct fillable attributes', function () {
    $task = new Task;

    expect($task->getFillable())->toBe([
        'title',
        'description',
        'completed',
    ]);
});

test('task model has correct casts', function () {
    $task = new Task;

    expect($task->getCasts())->toMatchArray([
        'completed' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ]);
});
