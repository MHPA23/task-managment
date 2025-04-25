<?php

namespace App\Http\Controllers;

use App\Actions\CreateTaskAction;
use App\Actions\GetTasksAction;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function index(Request $request, GetTasksAction $getTasksAction): AnonymousResourceCollection
    {
        return TaskResource::collection($getTasksAction->handle($request->all()));
    }

    public function store(TaskRequest $request, CreateTaskAction $createTaskAction): TaskResource|JsonResponse
    {
        try {
            $task = $createTaskAction->handle($request->validated());

            return new TaskResource($task);
        } catch (\Exception $e) {
            return response()->json(['error' => trans('message.error_to_saved_task')], 500);
        }
    }

    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task): TaskResource|JsonResponse
    {
        try {
            $task->update($request->validated());

            return new TaskResource($task);
        } catch (\Throwable $th) {
            return response()->json(['error' => trans('message.error_to_updated_task')], 500);
        }
    }

    public function destroy(Task $task): \Illuminate\Http\Response
    {
        $task->delete();

        return response()->noContent();
    }
}
