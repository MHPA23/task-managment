<?php

namespace App\Repository\Disk;

use App\Repository\Interface\RepositoryInterface;

class DiskRepository implements RepositoryInterface
{
    // Create DisckRepository class only for testing repository bin at app/Providers/AppServiceProvider
    protected string $diskName;

    public function setCollectionName(string $diskName): void
    {
        $this->diskName = $diskName;
    }

    public function save(array $entity): bool
    {
        $data = [];

        if (file_exists($this->diskName)) {
            $data = json_decode(file_get_contents($this->diskName), true) ?? [];
        }

        $data[] = $entity;

        return file_put_contents($this->diskName, json_encode($data, JSON_PRETTY_PRINT)) !== false;
    }
}
