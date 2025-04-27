<?php

namespace App\Interface;

use App\Models\Task;

interface GetTasksDashboardActionInterface
{
    public function handle(int $data): ?Task;
}
