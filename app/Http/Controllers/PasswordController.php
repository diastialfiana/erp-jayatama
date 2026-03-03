<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    public function showChangeForm()
    {
        return view('auth.password.change');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => 'required|min:4|confirmed',
        ]);

        $user = Auth::user();

        // Check if the new password is the same as the old NIP
        if ($request->password === $user->nip) {
            return back()->withErrors(['password' => 'Password baru tidak boleh sama dengan NIP.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false
        ]);

        return redirect()->route('dashboard')->with('success', 'Password berhasil diubah.');
    }
}
