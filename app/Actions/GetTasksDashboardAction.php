<?php

namespace App\Actions;

use App\Interface\GetTasksDashboardActionInterface;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class GetTasksDashboardAction implements GetTasksDashboardActionInterface
{
    public function handle(?int $userId = null): array
    {
        /**
         * Retrieve the task distribution by category using a join query
         * when using with by eloquent it will load all the tasks and then group them
         * which is not efficient for large datasets, I test with more than 125000 tasks
         * and it took more than 4 seconds to load the data, but with this query it took less than 350 ms
         */
        $categoryDistribution = Task::query()
            ->select('categories.name as category_name', DB::raw('COUNT(tasks.id) as count'))
            ->join('categories', 'categories.id', '=', 'tasks.category_id')
            ->when($userId, fn ($query) => $query->where('tasks.user_id', $userId))
            ->groupBy('categories.name')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category_name ?? 'Uncategorized',
                    'count' => $item->count,
                ];
            });

        // Retrieve the task summary separetely by completed, pending and overdue
        $tasksSummary = Task::query()
            ->select(
                DB::raw('SUM(CASE WHEN completed = true THEN 1 ELSE 0 END) as completed_tasks'),
                DB::raw('SUM(CASE WHEN completed = false THEN 1 ELSE 0 END) as pending_tasks'),
                DB::raw('SUM(CASE WHEN completed = false AND due_date <= NOW() THEN 1 ELSE 0 END) as overdue_tasks')
            )
            ->when($userId, fn ($query) => $query->where('user_id', $userId))
            ->first();

        return [
            'completedTasks' => $tasksSummary->completed_tasks,
            'pendingTasks' => $tasksSummary->pending_tasks,
            'overdueTasks' => $tasksSummary->overdue_tasks,
            'totalTasks' => $tasksSummary->completed_tasks + $tasksSummary->pending_tasks + $tasksSummary->overdue_tasks,
            'categoryDistribution' => $categoryDistribution,
        ];
    }
}
