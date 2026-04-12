<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AdminProfilController extends Controller
{
    public function show()
    {
        return view('admin.profil');
    }

    public function update(Request $request)
    {
        @ini_set('memory_limit', '128M');
        $user = auth()->user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => ['required', 'string', 'max:255', 'unique:users,email,' . $user->id, 'regex:/^.+@.+\..+$/i'],
            'no_hp'  => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:4096',
        ], [
            'email.regex' => 'Format email tidak valid (Gunakan format standard: contoh@email.com).',
        ]);

        $data = $request->only('name', 'email', 'no_hp', 'alamat');

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($user->banner) {
                Storage::disk('public')->delete($user->banner);
            }
            $data['banner'] = $request->file('banner')->store('banners', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }

    public function uploadAvatar(Request $request)
    {
        @ini_set('memory_limit', '128M');
        $request->validate(['avatar' => 'required|image|max:2048']);
        $user = auth()->user();
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);
        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function uploadBanner(Request $request)
    {
        @ini_set('memory_limit', '128M');
        $request->validate(['banner' => 'required|image|max:4096']);
        $user = auth()->user();
        if ($user->banner) {
            Storage::disk('public')->delete($user->banner);
        }
        $path = $request->file('banner')->store('banners', 'public');
        $user->update(['banner' => $path]);
        return back()->with('success', 'Foto banner berhasil diperbarui.');
    }
}
