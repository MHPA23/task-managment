<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateTaskAction;
use App\Actions\GetTasksAction;
use App\Actions\GetTasksDashboardAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TasksDashboardResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    public function index(Request $request, GetTasksAction $getTasksAction): AnonymousResourceCollection
    {
        return TaskResource::collection($getTasksAction->handle($request->all()));
    }

    public function store(TaskRequest $request, CreateTaskAction $createTaskAction): TaskResource|JsonResponse
    {

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $task = $createTaskAction->handle($data);

        return new TaskResource($task);

    }

    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task): TaskResource|JsonResponse
    {
        $task->update($request->validated());

        return new TaskResource($task);
    }

    public function destroy(Task $task): \Illuminate\Http\Response
    {
        $task->delete();

        return response()->noContent();
    }

    public function dashboard(GetTasksDashboardAction $getTasksDashboardAction): TasksDashboardResource
    {
        return Cache::remember('task_stats_'.auth()->user()->id, 60, function () use ($getTasksDashboardAction) {
            $taskStats = $getTasksDashboardAction->handle(auth()->user()->id);

            return new TasksDashboardResource($taskStats);
        });
    }
}
