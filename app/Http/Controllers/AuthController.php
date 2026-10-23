<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private readonly UserService $userService) {}

    /**
     * Display the login page.
     */
    public function login(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route($this->redirectRouteFor(Auth::user()));
        }

        return view('auth.login');
    }

    /**
     * Authenticate the user and start a session.
     */
    public function authLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials['email'] = Str::lower(trim($credentials['email']));

        if (! $this->userService->login($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()
            ->intended(route($this->redirectRouteFor($request->user())))
            ->with('success', 'Selamat datang kembali, '.$request->user()->name.'.');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(): RedirectResponse
    {
        $this->userService->logout();

        return redirect()
            ->route('login')
            ->with('success', 'Anda berhasil logout.');
    }

    /**
     * Get the route name the authenticated user should be redirected to.
     */
    private function redirectRouteFor(User $user): string
    {
        return $user->isAdmin() ? 'admin.dashboard' : 'home';
    }

    public function refreshToken() {}
}
