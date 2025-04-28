<?php

namespace App\Repository\Interface;

interface RepositoryInterface
{
    public function setCollectionName(string $collectionName): void;

    public function save(array $entity): bool;
}
