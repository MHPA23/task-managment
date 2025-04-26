<?php

use App\Models\Task;

test('task model has correct fillable attributes', function () {
    $task = new Task;

    expect($task->getFillable())->toBe([
        'title',
        'description',
        'due_date',
        'user_id',
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

test('task model has correct relationships', function () {
    $task = new Task;

    expect($task->user())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
