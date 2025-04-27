<?php

namespace App\Actions;

use App\Interface\GetTasksDashboardActionInterface;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class GetTasksDashboardAction implements GetTasksDashboardActionInterface
{
    public function handle(?int $userId = null): ?Task
    {
        return Task::query()
            ->select(
                DB::raw('SUM(CASE WHEN completed = true THEN 1 ELSE 0 END) as completed_tasks'),
                DB::raw('SUM(CASE WHEN completed = false THEN 1 ELSE 0 END) as pending_tasks'),
                DB::raw('SUM(CASE WHEN due_date <= NOW() THEN 1 ELSE 0 END) as overdue_tasks')
            )
            ->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->first();
    }
}
