<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{
    public function __construct(protected Role $model) {}

    public function all(): Collection
    {
        return $this->model->orderBy('id')->get();
    }

    public function findById(int $id): ?Role
    {
        return $this->model->find($id);
    }

    public function findByName(string $name): ?Role
    {
        return $this->model->where('name', $name)->first();
    }

    public function pluck(): \Illuminate\Support\Collection
    {
        return $this->model->orderBy('id')->pluck('label', 'id');
    }
}