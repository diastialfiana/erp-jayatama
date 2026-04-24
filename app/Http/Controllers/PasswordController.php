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
            'password' => 'required|min:6|confirmed',
        ], [
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        $user = Auth::user();

        // Prevent setting password to the default "jayatama"
        if ($request->password === 'jayatama') {
            return back()->withErrors(['password' => 'Anda tidak boleh menggunakan password default "jayatama".']);
        }

        // Check if the new password is the same as the old username
        if ($request->password === $user->username) {
            return back()->withErrors(['password' => 'Password baru tidak boleh sama dengan Username.']);
        }

        $user->update([
            'password'            => Hash::make($request->password),
            'must_change_password' => false
        ]);

        // Gunakan getRedirectRoute() agar user diarahkan ke modul yang sesuai aksesnya
        $redirectRoute = app(AuthController::class)->getFirstAccessibleRoute($user);

        return redirect()->route($redirectRoute)->with('success', 'Password berhasil diubah.');
    }
}

