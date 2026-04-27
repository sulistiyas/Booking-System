<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected RoleRepository $roleRepository,
    ) {}

    // ── Read ──────────────────────────────────────────────────────────────

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->paginate($filters, $perPage);
    }

    public function findOrFail(int $id): User
    {
        $user = $this->userRepository->findById($id, ['role']);

        if (! $user) {
            abort(404, 'User tidak ditemukan.');
        }

        return $user;
    }

    public function getRolesForForm(): \Illuminate\Support\Collection
    {
        return $this->roleRepository->pluck();
    }

    public function getStats(): array
    {
        $byRole = $this->userRepository->countByRole();

        return [
            'total'    => $byRole->sum('total'),
            'by_role'  => $byRole->mapWithKeys(fn($r) => [
                $r->role->name => $r->total,
            ]),
        ];
    }

    // ── Write ─────────────────────────────────────────────────────────────

    public function create(array $data): User
    {
        $this->ensureEmailUnique($data['email']);

        return $this->userRepository->create([
            'role_id'   => $data['role_id'],
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'phone'     => $data['phone'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function update(User $user, array $data): User
    {
        $this->ensureEmailUnique($data['email'], $user->id);

        $payload = [
            'role_id'   => $data['role_id'],
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'is_active' => $data['is_active'] ?? $user->is_active,
        ];

        // Hanya update password jika diisi
        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $this->userRepository->update($user, $payload);

        return $user->fresh('role');
    }

    public function delete(User $user): void
    {
        // Guard: tidak bisa hapus diri sendiri
        if ($user->id === Auth::id()) {
            throw ValidationException::withMessages([
                'user' => 'Anda tidak dapat menghapus akun sendiri.',
            ]);
        }

        // Guard: tidak bisa hapus satu-satunya admin
        if ($user->role->name === 'admin' && $this->isLastAdmin($user)) {
            throw ValidationException::withMessages([
                'user' => 'Tidak dapat menghapus admin terakhir.',
            ]);
        }

        $this->userRepository->delete($user);
    }

    public function toggleActive(User $user): User
    {
        // Guard: tidak bisa nonaktifkan diri sendiri
        if ($user->id === Auth::id()) {
            throw ValidationException::withMessages([
                'user' => 'Anda tidak dapat menonaktifkan akun sendiri.',
            ]);
        }

        $this->userRepository->toggleActive($user);

        return $user->fresh();
    }

    // ── Private Helpers ───────────────────────────────────────────────────

    private function ensureEmailUnique(string $email, ?int $excludeId = null): void
    {
        if ($this->userRepository->emailExists($email, $excludeId)) {
            throw ValidationException::withMessages([
                'email' => 'Email sudah digunakan oleh user lain.',
            ]);
        }
    }

    private function isLastAdmin(User $user): bool
    {
        return User::whereHas('role', fn($q) => $q->where('name', 'admin'))
            ->where('id', '!=', $user->id)
            ->doesntExist();
    }
}