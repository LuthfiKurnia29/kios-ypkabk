<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;

class UserService extends BaseService
{
    /**
     * Attempt to authenticate a user with the given credentials.
     *
     * @param  array{email: string, password: string}  $credentials
     */
    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
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

    public function refreshToken() {}

    public function getDataUser() {}

    public function createDataUser() {}

    public function updateDataUser() {}

    public function deleteDataUser() {}
}
