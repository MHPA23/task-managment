<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TaskStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        return Cache::remember('task_stats_'.auth()->user()->id, 60, function () {
            $taskStats = Task::query()
                ->select(
                    DB::raw('SUM(CASE WHEN completed = true THEN 1 ELSE 0 END) as completed_tasks'),
                    DB::raw('SUM(CASE WHEN completed = false THEN 1 ELSE 0 END) as pending_tasks'),
                    DB::raw('SUM(CASE WHEN due_date <= NOW() THEN 1 ELSE 0 END) as overdue_tasks')
                )
                ->where('user_id', auth()->user()->id)
                ->first();

            $stats = [
                'completedTasks' => (int) $taskStats->completed_tasks ?? 0,
                'pendingTasks' => (int) $taskStats->pending_tasks ?? 0,
                'overdueTasks' => (int) $taskStats->overdue_tasks ?? 0,
                'totalTasks' => (int) $taskStats->completed_tasks + (int) $taskStats->pending_tasks + (int) $taskStats->overdue_tasks,
            ];

            return response()->json($stats);
        });
    }
}
