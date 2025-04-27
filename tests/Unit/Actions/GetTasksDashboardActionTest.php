<?php

use App\Actions\GetTasksDashboardAction;
use App\Models\Category;
use App\Models\Task;

it('should return tasks values from dashboard', function () {
    Task::factory()->count(5)->create([
        'completed' => true,
        'due_date' => now()->addDays(1),
        'category_id' => Category::factory()->create([
            'name' => 'feature',
        ]),
    ]);

    Task::factory()->count(3)->create([
        'completed' => false,
        'due_date' => now()->addDays(1),
        'category_id' => Category::factory()->create([
            'name' => 'bug',
        ]),
    ]);
    $action = new GetTasksDashboardAction;
    $result = $action->handle();

    expect($result['completedTasks'])->toEqual(5);
    expect($result['pendingTasks'])->toEqual(3);
    expect(collect($result['categoryDistribution']->toArray())->pluck('count', 'category')->all())
        ->toEqual([
            'feature' => 5,
            'bug' => 3,
        ]);
});
