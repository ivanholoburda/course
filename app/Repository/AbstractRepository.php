<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    /**
     * @throws \Exception
     */
    public function create(array $data): Model
    {
        $modelName = $this->model();
        $model = new $modelName();

        return $this->update($model, $data);
    }

    /**
     * @throws \Exception
     */
    public function update(Model $model, array $data): ?Model
    {
        $model->fill($data);

        if (!$model->save()) {
            throw new \Exception("Failed to save model " . $model->getTable() . " id = $model->id");
        }

        return $model;
    }

    public function delete(int $id): bool
    {
        $model = $this->find($id);

        if (!$model) {
            return false;
        }

        return $model->delete();
    }

    public function find(int $id): ?Model
    {
        $modelName = $this->model();

        return $modelName::query()->find($id);
    }

    public function query(): Builder
    {
        $modelName = $this->model();
        return $modelName::query();
    }

    abstract public function model(): string;
}
