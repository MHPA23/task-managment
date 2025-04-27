<?php

namespace App\Interface;

interface GetTasksActionInterface
{
    /**
     * Handle the action of getting tasks.
     *
     * @param  array  $filters  Filters to apply to the task query.
     * @param  int|null  $userId  The ID of the user to filter tasks by.
     */
    public function handle(array $filters = [], ?int $userId = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}
