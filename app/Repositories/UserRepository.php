<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function __construct(protected User $model) {}

    // ── Read ──────────────────────────────────────────────────────────────

    public function findById(int $id, array $relations = []): ?User
    {
        return $this->model
            ->with($relations)
            ->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model
            ->where('email', $email)
            ->first();
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with('role')
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $q->where(function ($q) use ($filters) {
                    $q->where('name', 'like', "%{$filters['search']}%")
                      ->orWhere('email', 'like', "%{$filters['search']}%");
                });
            })
            ->when(isset($filters['role_id']), fn($q) => $q->where('role_id', $filters['role_id']))
            ->when(isset($filters['is_active']), fn($q) => $q->where('is_active', $filters['is_active']))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function all(array $filters = []): Collection
    {
        return $this->model
            ->with('role')
            ->when(isset($filters['role_id']), fn($q) => $q->where('role_id', $filters['role_id']))
            ->when(isset($filters['is_active']), fn($q) => $q->where('is_active', $filters['is_active']))
            ->latest()
            ->get();
    }

    public function countByRole(): Collection
    {
        return $this->model
            ->selectRaw('role_id, count(*) as total')
            ->with('role:id,name,label')
            ->groupBy('role_id')
            ->get();
    }

    // ── Write ─────────────────────────────────────────────────────────────

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function toggleActive(User $user): bool
    {
        return $user->update(['is_active' => ! $user->is_active]);
    }

    // ── Checks ────────────────────────────────────────────────────────────

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        return $this->model
            ->where('email', $email)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }
}