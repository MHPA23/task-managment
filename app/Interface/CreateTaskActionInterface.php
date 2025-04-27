<?php

namespace App\Interface;

use App\Models\Task;

interface CreateTaskActionInterface
{
    /**
     * Handle the action of creating a task.
     *
     * @param  array  $data  The data to create the task with.
     */
    public function handle(array $data): Task;
}
