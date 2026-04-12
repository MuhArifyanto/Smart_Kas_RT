<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    // Halaman semua notifikasi (warga & admin)
    public function index()
    {
        $notifikasi = auth()->user()
            ->notifikasi()
            ->latest()
            ->paginate(15);

        // Tandai semua sebagai dibaca saat halaman dibuka
        auth()->user()->notifikasi()->whereNull('dibaca_at')->update(['dibaca_at' => now()]);

        return view('notifikasi.index', compact('notifikasi'));
    }

    // Tandai satu notifikasi sebagai dibaca (via AJAX)
    public function baca(Notifikasi $notifikasi)
    {
        if ($notifikasi->user_id !== auth()->id()) {
            abort(403);
        }

        $notifikasi->update(['dibaca_at' => now()]);

        return response()->json(['success' => true]);
    }

    // Tandai semua sebagai dibaca
    public function bacaSemua()
    {
        auth()->user()->notifikasi()->whereNull('dibaca_at')->update(['dibaca_at' => now()]);

        return back()->with('status', 'Semua notifikasi telah ditandai dibaca.');
    }

    // Hapus notifikasi
    public function hapus(Notifikasi $notifikasi)
    {
        if ($notifikasi->user_id !== auth()->id()) {
            abort(403);
        }

        $notifikasi->delete();

        return back();
    }

    // Admin: kirim notifikasi pengingat ke semua warga belum bayar
    public function kirimPengingat(Request $request)
    {
        $request->validate(['pesan' => 'required|string|max:500']);

        $warga = \App\Models\User::where('role', 'warga')->get();

        foreach ($warga as $w) {
            Notifikasi::create([
                'user_id' => $w->id,
                'judul'   => 'Pengingat Pembayaran Iuran',
                'pesan'   => $request->pesan,
                'tipe'    => 'peringatan',
            ]);
        }

        return back()->with('status', 'Pengingat berhasil dikirim ke ' . $warga->count() . ' warga.');
    }

    // Admin: kirim notifikasi konfirmasi pembayaran ke warga tertentu
    public function kirimKonfirmasi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'judul'   => 'required|string|max:255',
            'pesan'   => 'required|string|max:500',
            'tipe'    => 'in:info,sukses,peringatan',
        ]);

        Notifikasi::create([
            'user_id' => $request->user_id,
            'judul'   => $request->judul,
            'pesan'   => $request->pesan,
            'tipe'    => $request->tipe ?? 'info',
        ]);

        return back()->with('status', 'Notifikasi berhasil dikirim.');
    }

    // Dropdown data (JSON) untuk topbar
    public function dropdown()
    {
        $items = auth()->user()
            ->notifikasi()
            ->latest()
            ->limit(8)
            ->get(['id', 'judul', 'pesan', 'tipe', 'dibaca_at', 'created_at']);

        $belumDibaca = auth()->user()->jumlahNotifikasiBelumDibaca();

        return response()->json(['items' => $items, 'belum_dibaca' => $belumDibaca]);
    }
}
