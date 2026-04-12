<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Login Google gagal. Silakan coba lagi.');
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update google_id dan avatar jika belum ada
            $user->update([
                'google_id'         => $googleUser->getId(),
                'avatar'            => $user->avatar ?? $googleUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
        } else {
            // Buat user baru sebagai warga
            $user = User::create([
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'google_id'         => $googleUser->getId(),
                'avatar'            => $googleUser->getAvatar(),
                'role'              => 'warga',
                'email_verified_at' => now(),
                'password'          => null,
            ]);
        }

        Auth::login($user);

        return redirect()->intended(route('dashboard'));
    }
}
