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
            'completedTasks' => $task['completedTasks'],
            'pendingTasks' => $task['pendingTasks'],
            'overdueTasks' => $task['overdueTasks'],
            'totalTasks' => $task['completedTasks'] + $task['pendingTasks'] + $task['overdueTasks'],
            'categoryDistribution' => $task['categoryDistribution'],
        ];
    }
}
