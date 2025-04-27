<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TasksDashboardResource;
use App\Interface\CreateTaskActionInterface;
use App\Interface\GetTasksActionInterface;
use App\Interface\GetTasksDashboardActionInterface;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    public function __construct(private CreateTaskActionInterface $createTaskAction,
        private GetTasksActionInterface $getTasksAction,
        private GetTasksDashboardActionInterface $getTasksDashboardAction) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return TaskResource::collection($this->getTasksAction->handle($request->all()));
    }

    public function store(TaskRequest $request): TaskResource|JsonResponse
    {

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $task = $this->createTaskAction->handle($data);

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

    public function dashboard(): TasksDashboardResource
    {
        return Cache::remember('task_stats_'.auth()->user()->id, 60, function () {
            $taskStats = $this->getTasksDashboardAction->handle(auth()->user()->id);

            return new TasksDashboardResource($taskStats);
        });
    }
}
