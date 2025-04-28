<?php

namespace App\Repository;

use App\Repository\Interface\RepositoryInterface;

class Repository implements Interface\RepositoryInterface
{
    public function __construct(
        protected RepositoryInterface $repository
    ) {}

    public function setCollectionName(string $collectionName): void
    {
        $this->repository->setCollectionName($collectionName);
    }

    public function save(array $entity): bool
    {
        return $this->repository->save($entity);
    }
}
