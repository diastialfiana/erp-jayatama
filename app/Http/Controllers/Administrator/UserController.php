<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        if (auth()->user()->isUser()) abort(403);
        $users = User::latest()->get();
        return view('administrator.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->isUser()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'role' => 'required|in:super_admin,admin,user',
            'status' => 'required|in:active,inactive',
        ]);

        if (auth()->user()->isAdmin() && $request->role === 'super_admin') {
            abort(403, 'Admins cannot create super admins.');
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make('jayatama'),
            'role' => $request->role,
            'status' => $request->status,
            'must_change_password' => true,
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->isUser()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'role' => 'required|in:super_admin,admin,user',
            'status' => 'required|in:active,inactive',
        ]);

        // Prevent admin from editing super_admin
        if ($user->role === 'super_admin' && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Admins cannot edit super admins.');
        }

        // Prevent admin from promoting someone to super_admin
        if (auth()->user()->isAdmin() && $request->role === 'super_admin') {
            abort(403, 'Admins cannot assign the super admin role.');
        }

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return response()->json(['success' => true]);
    }

    public function destroy(User $user)
    {
        if (auth()->user()->isUser()) abort(403);

        // Prevent deleting super_admin
        if ($user->role === 'super_admin' && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Admins cannot delete super admins.');
        }

        // Prevent deleting yourself
        if (auth()->id() === $user->id) {
            abort(403, 'Cannot delete yourself.');
        }

        $user->delete();

        return response()->json(['success' => true]);
    }

    public function resetPassword(User $user)
    {
        if (auth()->user()->isUser()) abort(403);

        if ($user->role === 'super_admin' && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Admins cannot reset super admins.');
        }

        // Default password = jayatama
        $user->update([
            'password' => Hash::make('jayatama'),
            'must_change_password' => true
        ]);

        return response()->json(['success' => true]);
    }
}
