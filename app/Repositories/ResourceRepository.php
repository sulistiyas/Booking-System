<?php

namespace App\Repositories;

use App\Models\Resource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ResourceRepository
{
    public function __construct(protected Resource $model) {}

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with('resourceType')
            ->when(isset($filters['search']), fn($q) =>
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('code', 'like', "%{$filters['search']}%")
            )
            ->when(isset($filters['resource_type_id']), fn($q) => $q->where('resource_type_id', $filters['resource_type_id']))
            ->when(isset($filters['is_active']),        fn($q) => $q->where('is_active', $filters['is_active']))
            ->orderBy('sort_order')
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $id, array $relations = []): ?Resource
    {
        return $this->model->with($relations)->find($id);
    }

    public function allActive(array $filters = []): Collection
    {
        return $this->model
            ->with('resourceType')
            ->where('is_active', true)
            ->when(isset($filters['resource_type_id']), fn($q) => $q->where('resource_type_id', $filters['resource_type_id']))
            ->orderBy('sort_order')
            ->get();
    }

    public function create(array $data): Resource
    {
        return $this->model->create($data);
    }

    public function update(Resource $resource, array $data): bool
    {
        return $resource->update($data);
    }

    public function delete(Resource $resource): bool
    {
        return $resource->delete();
    }
}