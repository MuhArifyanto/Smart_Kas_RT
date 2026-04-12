<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FirebaseController extends Controller
{
    /**
     * Verifikasi Firebase ID Token dan login/register user.
     * Digunakan untuk login via Google/Firebase di halaman welcome.
     */
    public function callback(Request $request)
    {
        $request->validate(['id_token' => 'required|string']);

        $idToken = $request->id_token;
        $apiKey  = config('services.firebase.api_key');

        $response = Http::post(
            "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key={$apiKey}",
            ['idToken' => $idToken]
        );

        if (! $response->successful() || empty($response->json('users'))) {
            return response()->json(['error' => 'Token tidak valid'], 401);
        }

        $firebaseUser = $response->json('users')[0];

        $email       = $firebaseUser['email'] ?? null;
        $name        = $firebaseUser['displayName'] ?? ($email ? explode('@', $email)[0] : 'User');
        $avatar      = $firebaseUser['photoUrl'] ?? null;
        $firebaseUid = $firebaseUser['localId'];

        if (! $email) {
            return response()->json(['error' => 'Email tidak ditemukan'], 400);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update([
                'google_id' => $firebaseUid,
                'avatar'    => $user->avatar ?? $avatar,
            ]);
        } else {
            $user = User::create([
                'name'              => $name,
                'email'             => $email,
                'google_id'         => $firebaseUid,
                'avatar'            => $avatar,
                'role'              => 'warga',
                'email_verified_at' => now(),
                'password'          => null,
            ]);
        }

        Auth::login($user);
        request()->session()->regenerate();

        $redirect = $user->role === 'admin'
            ? route('admin.dashboard')
            : route('warga.dashboard');

        return response()->json(['redirect' => $redirect]);
    }

    /**
     * Verifikasi nomor telepon via Firebase Phone Auth.
     *
     * Alur:
     * 1. Client-side: Firebase JS SDK kirim OTP ke nomor HP warga
     * 2. Client-side: warga input OTP → Firebase return idToken
     * 3. Client kirim idToken & no_hp ke endpoint ini
     * 4. Backend verifikasi idToken ke Firebase REST API
     * 5. Update phone_verified_at & no_hp pada user yang sedang login
     */
    public function verifyPhone(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
            'no_hp'    => 'required|string|max:20',
        ]);

        if (! Auth::check()) {
            return response()->json(['error' => 'Tidak terautentikasi'], 401);
        }

        $apiKey  = config('services.firebase.api_key');
        $idToken = $request->id_token;

        // Verifikasi token ke Firebase
        $response = Http::post(
            "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key={$apiKey}",
            ['idToken' => $idToken]
        );

        if (! $response->successful() || empty($response->json('users'))) {
            return response()->json(['error' => 'Token verifikasi tidak valid atau sudah kedaluwarsa.'], 401);
        }

        $firebaseUser = $response->json('users')[0];

        // Pastikan nomor HP dari Firebase cocok
        $phoneFromFirebase = $firebaseUser['phoneNumber'] ?? null;

        if (! $phoneFromFirebase) {
            return response()->json(['error' => 'Token tidak memiliki data nomor telepon.'], 400);
        }

        // Normalisasi nomor: Firebase kirim format +62xxx, kita simpan 08xxx
        $noHpBersih = $request->no_hp;

        // Update user
        $user = Auth::user();
        $user->update([
            'no_hp'             => $noHpBersih,
            'phone_verified_at' => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Nomor telepon berhasil diverifikasi!',
            'no_hp'   => $noHpBersih,
        ]);
    }
}
