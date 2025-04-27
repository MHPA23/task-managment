<?php

use App\Models\Task;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $user = User::factory()->create();
    actingAs($user);
});

test('should be possible list tasks', function () {
    Task::factory()->count(10)->create([
        'user_id' => auth()->id(),
    ]);

    $response = $this->getJson(route('tasks.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'title',
                'description',
                'completed',
            ],
        ],
    ]);
    $response->assertJsonCount(10, 'data');
});

test('should be possible to create a task', function () {
    $taskData = Task::factory()->make()->toArray();

    $response = $this->postJson(route('tasks.store'), $taskData);

    $response->assertStatus(201);
    $response->assertJsonStructure([
        'data' => [
            'id',
            'title',
            'description',
            'due_date',
            'completed',
        ],
    ]);
    $this->assertDatabaseHas('tasks', [
        'title' => $taskData['title'],
        'description' => $taskData['description'],
    ]);
});

it('should be possible validate if title and description are required', function () {
    $response = $this->postJson(route('tasks.store'), [
        'title' => '',
        'description' => '',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['title', 'description']);
});

test('should be possible to show a task', function () {
    $task = Task::factory()->create();

    $response = $this->getJson(route('tasks.show', $task));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'id',
            'title',
            'description',
            'completed',
        ],
    ]);
});

test('should be possible to update a task', function () {
    $task = Task::factory()->create();
    $taskData = Task::factory()->make()->toArray();

    $response = $this->putJson(route('tasks.update', $task), $taskData);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'id',
            'title',
            'description',
            'completed',
        ],
    ]);
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => $taskData['title'],
        'description' => $taskData['description'],
    ]);
});

it('should be possible validate if title and description are required when update tasks', function () {
    $task = Task::factory()->create();

    $response = $this->putJson(route('tasks.update', $task), [
        'title' => '',
        'description' => '',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['title', 'description']);
});

test('should be possible to delete a task', function () {
    $task = Task::factory()->create();

    $response = $this->deleteJson(route('tasks.destroy', $task));

    $response->assertStatus(204);
    $this->assertDatabaseMissing('tasks', $task->toArray());
});

it('should be possible return tasks values for dashboard', function () {
    Task::factory()->create([
        'user_id' => auth()->id(),
        'completed' => false,
    ]);

    $response = $this->getJson(route('tasks.dashboard'));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'totalTasks',
            'completedTasks',
            'overdueTasks',
            'pendingTasks',
        ],
    ]);
    $response->assertJson([
        'data' => [
            'totalTasks' => 1,
            'completedTasks' => 0,
            'overdueTasks' => 0,
            'pendingTasks' => 1,
        ],
    ]);
});
