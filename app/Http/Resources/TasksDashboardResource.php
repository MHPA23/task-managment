<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TasksDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\Task $this */
        $task = $this;

        return [
            'completedTasks' => $task->completed_tasks,
            'pendingTasks' => $task->pending_tasks,
            'overdueTasks' => $task->overdue_tasks,
            'totalTasks' => $task->completed_tasks + $task->pending_tasks + $task->overdue_tasks,
        ];
    }
}
