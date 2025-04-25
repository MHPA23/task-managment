<?php

use App\Models\Task;

test('should be possible list tasks', function () {
    Task::factory()->count(10)->create();

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
            'completed',
        ],
    ]);
    $this->assertDatabaseHas('tasks', $taskData);
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
    $this->assertDatabaseHas('tasks', $taskData);
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
