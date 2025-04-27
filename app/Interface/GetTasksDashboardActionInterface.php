<?php

namespace App\Interface;

interface GetTasksDashboardActionInterface
{
    /**
     * Handle the action of getting tasks for the dashboard.
     *
     * @param  int|null  $userId  The ID of the user to filter tasks by.
     */
    public function handle(int $userId): array;
}
