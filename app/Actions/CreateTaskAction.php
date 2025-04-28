<?php

namespace App\Actions;

use App\Interface\CreateTaskActionInterface;
use App\Repository\Repository;

class CreateTaskAction implements CreateTaskActionInterface
{
    // Using the Repository pattern to save the task
    public function __construct(
        private readonly Repository $repository
    ) {}

    public function handle(array $data): bool
    {
        $this->repository->setCollectionName('task');

        return $this->repository->save($data);
    }
}
