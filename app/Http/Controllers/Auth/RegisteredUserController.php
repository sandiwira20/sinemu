<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Pastikan user baru selalu mendapat role "user"
        $role = Role::firstOrCreate(['name' => 'user']);
        $roleId = $role->id ?? null;

        if (!$roleId) {
            return back()->withErrors(['role' => 'Role "user" belum tersedia. Jalankan seeder roles terlebih dahulu.']);
        }

        $user = User::create([
            'role_id' => $roleId,
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => Str::lower($request->email),
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }
}
