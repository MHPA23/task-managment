<?php

namespace App\Interface;

use App\Models\Task;

interface CreateTaskActionInterface
{
    public function handle(array $data): Task;
}
