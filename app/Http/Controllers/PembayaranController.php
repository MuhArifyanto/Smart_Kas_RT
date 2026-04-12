<?php

namespace App\Http\Controllers;

use App\Mail\KwitansiPembayaran;
use App\Models\ActivityLog;
use App\Models\Iuran;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['user', 'iuran'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$s}%"));
        }

        $pembayaran = $query->paginate(10)->withQueryString();
        $summary = [
            'menunggu'  => Pembayaran::where('status', 'menunggu')->count(),
            'disetujui' => Pembayaran::where('status', 'disetujui')->count(),
            'ditolak'   => Pembayaran::where('status', 'ditolak')->count(),
        ];

        return view('admin.pembayaran', compact('pembayaran', 'summary'));
    }

    public function form(Request $request)
    {
        $iuranList = Iuran::where('user_id', auth()->id())
            ->whereIn('status', ['belum_bayar', 'menunggu'])
            ->latest()->get();

        $iuranId = $request->get('iuran_id');
        $iuran   = $iuranId ? Iuran::find($iuranId) : $iuranList->first();

        return view('warga.pembayaran', compact('iuranList', 'iuran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'iuran_id'    => 'required|exists:iuran,id',
            'metode'      => 'required|in:ewallet,qris,transfer_bank',
            'provider'    => 'required|string',
            'bukti_bayar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $iuran    = Iuran::where('id', $request->iuran_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $existing = Pembayaran::where('iuran_id', $iuran->id)
            ->whereIn('status', ['menunggu', 'disetujui'])->first();

        if ($existing) {
            return back()->with('error', 'Pembayaran untuk iuran ini sudah ada dan sedang diproses.');
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_bayar')) {
            $buktiPath = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        }

        // Langsung disetujui otomatis
        $pembayaran = Pembayaran::create([
            'iuran_id'    => $iuran->id,
            'user_id'     => auth()->id(),
            'metode'      => $request->metode,
            'provider'    => $request->provider,
            'jumlah'      => $iuran->nominal,
            'status'      => 'disetujui',
            'bukti_bayar' => $buktiPath,
            'dibayar_at'  => now(),
        ]);

        // Iuran langsung lunas
        $iuran->update(['status' => 'lunas', 'dibayar_at' => now()]);

        ActivityLog::catat('system_verify', 'Sistem memverifikasi pembayaran otomatis ' . auth()->user()->name . ' via ' . strtoupper($request->provider), 'emerald', 'cpu');

        // Notifikasi in-app
        Notifikasi::create([
            'user_id' => auth()->id(),
            'judul'   => 'Pembayaran Diverifikasi ✓',
            'pesan'   => 'Pembayaran iuran Anda sebesar Rp ' . number_format($iuran->nominal, 0, ',', '.') .
                         ' via ' . strtoupper($request->provider) . ' telah diverifikasi secara otomatis oleh sistem. Kwitansi tersedia di profil Anda.',
            'tipe'    => 'sukses',
        ]);

        // Kirim email kwitansi + PDF otomatis
        $pembayaran->load(['user', 'iuran']);
        try {
            if ($pembayaran->user->email) {
                Mail::to($pembayaran->user->email)->send(new KwitansiPembayaran($pembayaran));
            }
        } catch (\Throwable $e) {
            \Log::warning('SMTP Error: Gagal kirim email kwitansi ke ' . $pembayaran->user->email . ' - ' . $e->getMessage());
        }

        return back()->with('success', 'Pembayaran Berhasil! Terverifikasi otomatis oleh sistem.');
    }

    public function setujui(Pembayaran $pembayaran)
    {
        $pembayaran->load(['user', 'iuran']);
        $pembayaran->update(['status' => 'disetujui']);
        $pembayaran->iuran->update(['status' => 'lunas', 'dibayar_at' => now()]);

        ActivityLog::catat('pembayaran_disetujui', 'Admin menyetujui pembayaran ' . $pembayaran->user->name, 'green', 'check', auth()->id());

        Notifikasi::create([
            'user_id' => $pembayaran->user_id,
            'judul'   => 'Pembayaran Dikonfirmasi',
            'pesan'   => 'Pembayaran iuran Anda sebesar Rp ' . number_format($pembayaran->jumlah, 0, ',', '.') .
                         ' via ' . $pembayaran->labelMetode() . ' telah dikonfirmasi. Kwitansi dikirim ke email Anda.',
            'tipe'    => 'sukses',
        ]);

        // Kirim email kwitansi otomatis
        try {
            if ($pembayaran->user->email) {
                Mail::to($pembayaran->user->email)->send(new KwitansiPembayaran($pembayaran));
            }
        } catch (\Throwable $e) {
            \Log::warning('SMTP Error: Gagal kirim email kwitansi (Disetujui) ke ' . $pembayaran->user->email . ' - ' . $e->getMessage());
        }

        return back()->with('success', 'Pembayaran disetujui. Kwitansi dikirim ke email warga.');
    }

    public function tolak(Request $request, Pembayaran $pembayaran)
    {
        $pembayaran->update(['status' => 'ditolak', 'catatan' => $request->catatan]);
        $pembayaran->iuran->update(['status' => 'belum_bayar']);

        ActivityLog::catat('pembayaran_ditolak', 'Admin menolak pembayaran ' . ($pembayaran->user->name ?? '-'), 'red', 'x', auth()->id());

        Notifikasi::create([
            'user_id' => $pembayaran->user_id,
            'judul'   => 'Pembayaran Ditolak',
            'pesan'   => 'Pembayaran iuran Anda ditolak.' . ($request->catatan ? ' Alasan: ' . $request->catatan : ' Silakan hubungi admin.'),
            'tipe'    => 'peringatan',
        ]);

        return back()->with('success', 'Pembayaran ditolak.');
    }

    // Halaman kwitansi — print & download PDF
    public function kwitansi(Pembayaran $pembayaran)
    {
        if (auth()->id() !== $pembayaran->user_id && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $pembayaran->load(['user', 'iuran']);
        return view('warga.kwitansi', compact('pembayaran'));
    }

    // Polling untuk real-time histori (Admin)
    public function poll(Request $request)
    {
        $query = Pembayaran::with(['user', 'iuran'])->latest();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pembayaran = $query->paginate(10);
        $summary = [
            'menunggu'  => Pembayaran::where('status', 'menunggu')->count(),
            'disetujui' => Pembayaran::where('status', 'disetujui')->count(),
            'ditolak'   => Pembayaran::where('status', 'ditolak')->count(),
        ];

        // Membagi tampilan daftar dengan file blade partial
        $html = view('admin.partials.pembayaran_list', compact('pembayaran'))->render();

        return response()->json([
            'html' => $html,
            'summary' => $summary
        ]);
    }
}