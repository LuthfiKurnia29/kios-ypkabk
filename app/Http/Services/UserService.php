<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    /**
     * Attempt to authenticate a user with the given credentials.
     *
     * @param  array{email: string, password: string}  $credentials
     */
    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials);
    }

    /**
     * Log the current user out and invalidate their session.
     */
    public function logout(): void
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();
    }

    /**
     * Regenerate the session ID and CSRF token.
     */
    public function refreshToken(): void
    {
        session()->regenerateToken();
        session()->regenerate();
    }

    /**
     * Resolve a User instance from model, ID, or email.
     */
    public function resolveUser(User|int|string $user): User
    {
        if ($user instanceof User) {
            return $user;
        }

        if (is_numeric($user)) {
            return User::findOrFail((int) $user);
        }

        return User::where('email', Str::lower(trim($user)))->firstOrFail();
    }

    /**
     * Get user data by ID, email, instance, authenticated session, or all users.
     */
    public function getDataUser(User|int|string|null $user = null): User|Collection|null
    {
        if ($user !== null) {
            return $this->resolveUser($user);
        }

        return Auth::user() ?? User::latest()->get();
    }

    /**
     * Get users with optional filtering and pagination.
     *
     * @param  array{role?: ?string, search?: ?string}  $filters
     * @return LengthAwarePaginator<User>|Collection<int, User>
     */
    public function getUsers(array $filters = [], ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = User::query();

        if (! empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (! empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $query->latest();

        return $perPage !== null ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Create a new user record.
     *
     * @param  array{
     *     name: string,
     *     email: string,
     *     password: string,
     *     role?: string,
     * }  $data
     */
    public function createDataUser(array $data): User
    {
        return User::create([
            'name' => trim($data['name']),
            'email' => Str::lower(trim($data['email'])),
            'password' => $data['password'],
            'role' => $data['role'] ?? 'user',
        ]);
    }

    /**
     * Update an existing user record.
     *
     * @param  array{
     *     name?: string,
     *     email?: string,
     *     password?: ?string,
     *     role?: string,
     * }  $data
     */
    public function updateDataUser(User|int|string $user, array $data): User
    {
        $targetUser = $this->resolveUser($user);

        $attributes = [];

        if (isset($data['name'])) {
            $attributes['name'] = trim($data['name']);
        }

        if (isset($data['email'])) {
            $attributes['email'] = Str::lower(trim($data['email']));
        }

        if (! empty($data['password'])) {
            $attributes['password'] = $data['password'];
        }

        if (isset($data['role'])) {
            $attributes['role'] = $data['role'];
        }

        if (! empty($attributes)) {
            $targetUser->update($attributes);
        }

        return $targetUser->fresh();
    }

    /**
     * Delete a user record.
     */
    public function deleteDataUser(User|int|string $user): bool
    {
        $targetUser = $this->resolveUser($user);

        return (bool) $targetUser->delete();
    }
}
