<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    public function index()
    {
        return view('administrator.index');
    }

    public function menuVisibility()
    {
        // Get all users except Super Admin
        $users = \App\Models\User::where('role', '!=', 'super_admin')->get();
        $menus = \App\Models\Menu::all();

        // Eager load permissions for the view
        $permissions = \App\Models\MenuUserPermission::all()->groupBy('user_id');

        return view('administrator.menu-visibility', compact('users', 'menus', 'permissions'));
    }

    public function updateMenuVisibility(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'menu_id' => 'required|exists:menus,id',
            'can_view' => 'required|boolean',
        ]);

        \App\Models\MenuUserPermission::updateOrCreate(
            ['user_id' => $request->user_id, 'menu_id' => $request->menu_id],
            ['can_view' => $request->can_view]
        );

        // Clear cache for this user
        \Illuminate\Support\Facades\Cache::forget('user_menus_' . $request->user_id);

        return response()->json(['success' => true, 'message' => 'Access updated']);
    }
}
