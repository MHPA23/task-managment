<?php

namespace App\Actions;

use App\Interface\GetTasksActionInterface;

class GetTasksAction implements GetTasksActionInterface
{
    public function handle(array $filters = [], ?int $userId = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return \App\Models\Task::query()
            ->with(['category'])
            ->when($filters['completed'] ?? null, function ($query, $completed) {
                $query->where('completed', $completed);
            })
            ->when($filters['title'] ?? null, function ($query, $title) {
                $query->where('title', 'like', '%'.$title.'%');
            })
            ->when($filters['sort_by'] ?? null, function ($query, $sortBy) use ($filters) {
                $query->orderBy($sortBy, $filters['sort_dir'] ?? 'asc');
            })
            ->when(isset($filters['date_init']) && isset($filters['date_end']), function ($query) use ($filters) {
                $query->whereBetween('created_at', [$filters['date_init'], $filters['date_end']]);
            })
            ->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->paginate(10);
    }
}
