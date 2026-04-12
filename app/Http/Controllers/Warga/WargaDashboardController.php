<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class WargaDashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $iuranBulanIni = Iuran::where('user_id', $user->id)
            ->where('bulan', now()->format('Y-m'))->first();

        $totalLunas = Iuran::where('user_id', $user->id)->where('status', 'lunas')->count();
        $totalBelum = Iuran::where('user_id', $user->id)->whereIn('status', ['belum_bayar', 'menunggu'])->count();

        $riwayat = Pembayaran::where('user_id', $user->id)
            ->with('iuran')->latest()->limit(5)->get();

        return view('warga.dashboard', compact('iuranBulanIni', 'totalLunas', 'totalBelum', 'riwayat'));
    }

    public function iuran()
    {
        $iuran = Iuran::where('user_id', auth()->id())->with('pembayaran')->latest()->paginate(10);
        return view('warga.iuran', compact('iuran'));
    }

    public function riwayat()
    {
        $riwayat = Pembayaran::where('user_id', auth()->id())
            ->with('iuran')->latest()->paginate(10);
        return view('warga.riwayat', compact('riwayat'));
    }

    public function profil()
    {
        $user = auth()->user();

        // Data pembayaran 6 bulan terakhir untuk progress bar
        $bulanList = collect(range(5, 0))->map(function ($i) use ($user) {
            $bulan  = now()->subMonths($i)->format('Y-m');
            $label  = now()->subMonths($i)->translatedFormat('M Y');
            $iuran  = Iuran::where('user_id', $user->id)->where('bulan', $bulan)->first();
            return [
                'bulan'  => $bulan,
                'label'  => $label,
                'status' => $iuran?->status ?? 'tidak_ada',
                'iuran'  => $iuran,
            ];
        });

        $totalIuran  = Iuran::where('user_id', $user->id)->count();
        $totalLunas  = Iuran::where('user_id', $user->id)->where('status', 'lunas')->count();
        $persen      = $totalIuran > 0 ? round(($totalLunas / $totalIuran) * 100) : 0;

        return view('warga.profil', compact('user', 'bulanList', 'totalLunas', 'totalIuran', 'persen'));
    }

    public function updateProfil(Request $request)
    {
        // Tingkatkan memory limit untuk menghindari 500 error di hosting dengan RAM kecil
        @ini_set('memory_limit', '128M');

        $user = auth()->user();

        $request->validate([
            'name'   => 'required|string|max:255',
            // Gunakan regex sederhana untuk email demi menghemat memori (menghindari library email-validator)
            'email'  => ['required', 'string', 'max:255', 'unique:users,email,' . $user->id, 'regex:/^.+@.+\..+$/i'],
            'no_hp'  => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:4096',
        ], [
            'email.regex' => 'Format email tidak valid.',
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

    // Polling untuk real-time histori (Warga)
    public function pollRiwayat()
    {
        $user = auth()->user();
        $riwayat = Pembayaran::where('user_id', $user->id)
            ->with('iuran')->latest()->paginate(10);
        
        $riwayat_limit = Pembayaran::where('user_id', $user->id)
            ->with('iuran')->latest()->limit(5)->get();

        $html_full = view('warga.partials.riwayat_list', ['riwayat' => $riwayat])->render();
        $html_dashboard = view('warga.partials.recent_riwayat_list', ['riwayat' => $riwayat_limit])->render();

        return response()->json([
            'html_full' => $html_full,
            'html_dashboard' => $html_dashboard,
            'totalLunas' => Iuran::where('user_id', $user->id)->where('status', 'lunas')->count(),
            'totalBelum' => Iuran::where('user_id', $user->id)->whereIn('status', ['belum_bayar', 'menunggu'])->count(),
        ]);
    }
}
