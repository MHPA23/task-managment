<?php

namespace App\Actions;

class GetTasksAction
{
    public function handle(array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return \App\Models\Task::query()
            ->when($filters['completed'] ?? null, function ($query, $completed) {
                $query->where('completed', $completed);
            })
            ->when($filters['title'] ?? null, function ($query, $title) {
                $query->where('title', 'ilike', '%'.$title.'%');
            })
            ->when($filters['sort_by'] ?? null, function ($query, $sortBy) use ($filters) {
                $query->orderBy($sortBy, $filters['sort_dir'] ?? 'asc');
            })
            ->when(isset($filters['date_init']) && isset($filters['date_end']), function ($query) use ($filters) {
                $query->whereBetween('created_at', [$filters['date_init'], $filters['date_end']]);
            })
            ->latest()
            ->paginate(10);
    }
}
