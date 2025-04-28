<?php

namespace App\Repository\Eloquent;

use App\Repository\Interface\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use InvalidArgumentException;

class EloquentRepository implements RepositoryInterface
{
    protected Model $model;

    public function setCollectionName(string $collectionName): void
    {
        $modelClass = 'App\\Models\\'.Str::studly($collectionName);

        if (! class_exists($modelClass)) {
            throw new InvalidArgumentException("Model class '{$modelClass}' does not exist.");
        }

        $this->model = new $modelClass;
    }

    public function save(array $entity): bool
    {
        $model = new $this->model((array) $entity);

        return $model->save();
    }
}
