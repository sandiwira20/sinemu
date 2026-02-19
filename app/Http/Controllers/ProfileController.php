<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'display_name' => 'required|string|max:100',
            'full_name'    => 'nullable|string|max:150',
            'phone'        => 'nullable|string|max:20',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete(str_replace('storage/', '', $user->photo));
            }
            $path = $request->file('photo')->store('profile', 'public');
            $data['photo'] = 'storage/'.$path;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function deletePhoto(Request $request)
    {
        $user = auth()->user();
        if ($user->photo) {
            Storage::disk('public')->delete(str_replace('storage/', '', $user->photo));
            $user->update(['photo' => null]);
        }

        return back()->with('success', 'Foto profil dihapus.');
    }
}
