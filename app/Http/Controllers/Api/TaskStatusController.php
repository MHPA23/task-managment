<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaskStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        return Cache::remember('tasks.status', 60, function () {
            $tasksCompleted = Task::query()
                ->where('completed', true)
                ->count();

            $tasksPending = Task::query()
                ->where('completed', false)
                ->count();

            return response()->json([
                'completedTasks' => $tasksCompleted,
                'pendingTasks' => $tasksPending,
                'totalTasks' => $tasksCompleted + $tasksPending,
            ]);
        });
    }
}
