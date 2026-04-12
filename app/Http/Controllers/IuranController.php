<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IuranController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));

        $query = Iuran::with('user')->where('bulan', $bulan);

        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $iuran = $query->latest()->paginate(10)->withQueryString();

        $summary = [
            'lunas'       => Iuran::where('bulan', $bulan)->where('status', 'lunas')->count(),
            'menunggu'    => Iuran::where('bulan', $bulan)->where('status', 'menunggu')->count(),
            'belum_bayar' => Iuran::where('bulan', $bulan)->where('status', 'belum_bayar')->count(),
        ];

        return view('admin.iuran', compact('iuran', 'summary', 'bulan'));
    }

    // Generate tagihan untuk semua warga aktif bulan ini
    public function generate(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        $nominal = $request->get('nominal', 150000);

        $warga = User::where('role', 'warga')->where('status', 'aktif')->get();
        $dibuat = 0;

        DB::transaction(function () use ($warga, $bulan, $nominal, &$dibuat) {
            foreach ($warga as $w) {
                $exists = \App\Models\Iuran::where('user_id', $w->id)->where('bulan', $bulan)->exists();
                if (!$exists) {
                    \App\Models\Iuran::create([
                        'user_id' => $w->id,
                        'bulan'   => $bulan,
                        'nominal' => $nominal,
                        'status'  => 'belum_bayar',
                    ]);
                    // Kirim notifikasi ke warga
                    \App\Models\Notifikasi::create([
                        'user_id' => $w->id,
                        'judul'   => 'Tagihan Iuran ' . \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y'),
                        'pesan'   => 'Tagihan iuran bulan ' . \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') . ' sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' telah dibuat. Harap segera melakukan pembayaran.',
                        'tipe'    => 'peringatan',
                    ]);
                    $dibuat++;
                }
            }
        });

        \App\Models\ActivityLog::catat('generate_iuran', 'Admin membuat tagihan iuran ' . \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') . " untuk {$dibuat} warga", 'yellow', 'file');

        return back()->with('success', "Tagihan berhasil dibuat untuk {$dibuat} warga bulan " . \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') . '.');
    }

    // Update status iuran (admin konfirmasi)
    public function updateStatus(Request $request, Iuran $iuran)
    {
        $request->validate(['status' => 'required|in:lunas,menunggu,belum_bayar']);

        $iuran->update([
            'status'     => $request->status,
            'dibayar_at' => $request->status === 'lunas' ? now() : null,
        ]);

        // Kirim notifikasi ke warga jika dikonfirmasi lunas
        if ($request->status === 'lunas') {
            Notifikasi::create([
                'user_id' => $iuran->user_id,
                'judul'   => 'Pembayaran Dikonfirmasi',
                'pesan'   => 'Pembayaran iuran bulan ' . \Carbon\Carbon::parse($iuran->bulan . '-01')->translatedFormat('F Y') . ' Anda telah dikonfirmasi. Terima kasih!',
                'tipe'    => 'sukses',
            ]);
        }

        return back()->with('success', 'Status iuran berhasil diperbarui.');
    }

    public function destroy(Iuran $iuran)
    {
        $iuran->delete();
        return back()->with('success', 'Data iuran berhasil dihapus.');
    }
}
