<?php

use App\Actions\GetTasksDashboardAction;
use App\Models\Task;

it('should return tasks values from dashboard', function () {
    Task::factory()->count(5)->create([
        'completed' => true,
        'due_date' => now()->addDays(1),
    ]);

    Task::factory()->count(3)->create([
        'completed' => false,
        'due_date' => now()->addDays(1),
    ]);
    $action = new GetTasksDashboardAction;
    $result = $action->handle();

    expect($result['completedTasks'])->toEqual(5);
    expect($result['pendingTasks'])->toEqual(3);
});
