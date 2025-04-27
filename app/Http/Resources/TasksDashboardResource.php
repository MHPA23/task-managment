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
        return [
            'completedTasks' => $this->completed_tasks,
            'pendingTasks' => $this->pending_tasks,
            'overdueTasks' => $this->overdue_tasks,
            'totalTasks' => $this->completed_tasks + $this->pending_tasks + $this->overdue_tasks,
        ];
    }
}
