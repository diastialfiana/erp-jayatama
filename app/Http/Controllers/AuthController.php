<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route($this->getFirstAccessibleRoute(Auth::user()));
        }
        
        $users = User::where('status', 'active')->select('name', 'username')->get();
        return view('auth.login', compact('users'));
    }

    public function login(LoginRequest $request)
    {
        $this->ensureIsNotRateLimited($request);

        $loginInput = $request->input('name');
        $password = $request->input('password');

        // Extract username if input matches "Name (username)" pattern
        if (preg_match('/\((.*?)\)$/', $loginInput, $matches)) {
            $username = $matches[1];
        } else {
            $username = $loginInput;
        }

        $user = User::where('username', $username)->first();

        // Fallback to name search
        if (!$user) {
            $user = User::where('name', $loginInput)->first();
        }

        if (!$user) {
            RateLimiter::hit($this->throttleKey($request));
            return back()->withErrors([
                'name' => 'Nama tidak ditemukan',
            ])->onlyInput('name');
        }

        if (!Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey($request));
            return back()->withErrors([
                'password' => 'Password salah',
            ])->onlyInput('name');
        }

        if ($user->status !== 'active') {
            RateLimiter::hit($this->throttleKey($request));
            return back()->withErrors([
                'name' => 'Akun tidak aktif',
            ])->onlyInput('name');
        }

        RateLimiter::clear($this->throttleKey($request));

        Auth::login($user, $request->boolean('remember'));
        
        $user->last_login = now();
        $user->save();

        $request->session()->regenerate();

        return redirect()->route($this->getFirstAccessibleRoute($user));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'name' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('name')) . '|' . $request->ip();
    }

    /**
     * Determines the first accessible route for a given user.
     * Checks must_change_password first, then menu permissions.
     *
     * Public so it can be called from PasswordController.
     */
    public function getFirstAccessibleRoute($user): string
    {
        // If user must change password first, send them there
        if ($user->must_change_password) {
            return 'password.change';
        }

        if ($user->isSuperAdmin()) {
            return 'dashboard';
        }

        $firstPermission = $user->menuPermissions()->where('can_view', true)->first();
        if ($firstPermission && $firstPermission->menu) {
            $routes = [
                'Dashboard'          => 'dashboard',
                'Inventory & GA'     => 'inventory.index',
                'Finance'            => 'finance.index',
                'Accounting'         => 'accounting.index',
                'Administrator'      => 'administrator.index',
                'Panduan Penggunaan' => 'help.index',
            ];
            $menuName = $firstPermission->menu->name;
            if (isset($routes[$menuName])) {
                return $routes[$menuName];
            }
        }

        return 'dashboard';
    }
}
