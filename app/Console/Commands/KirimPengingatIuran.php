<?php

namespace App\Console\Commands;

use App\Models\Iuran;
use App\Models\Notifikasi;
use App\Models\ActivityLog;
use Illuminate\Console\Command;

class KirimPengingatIuran extends Command
{
    protected $signature   = 'iuran:pengingat';
    protected $description = 'Kirim notifikasi pengingat otomatis ke warga yang belum bayar';

    public function handle(): void
    {
        $bulan = now()->format('Y-m');

        // Ambil semua iuran belum bayar bulan ini
        $iuranBelumBayar = Iuran::with('user')
            ->where('bulan', $bulan)
            ->where('status', 'belum_bayar')
            ->get();

        $terkirim = 0;

        foreach ($iuranBelumBayar as $iuran) {
            if (!$iuran->user) continue;

            // Cek apakah sudah ada notifikasi pengingat hari ini
            $sudahAda = Notifikasi::where('user_id', $iuran->user_id)
                ->where('tipe', 'peringatan')
                ->whereDate('created_at', today())
                ->where('judul', 'like', '%Pengingat%')
                ->exists();

            if ($sudahAda) continue;

            Notifikasi::create([
                'user_id' => $iuran->user_id,
                'judul'   => 'Pengingat: Iuran Belum Dibayar',
                'pesan'   => 'Iuran bulan ' . \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') .
                             ' sebesar Rp ' . number_format($iuran->nominal, 0, ',', '.') .
                             ' belum dibayar. Jatuh tempo tanggal 28. Segera lakukan pembayaran.',
                'tipe'    => 'peringatan',
            ]);

            $terkirim++;
        }

        ActivityLog::catat(
            'kirim_pengingat',
            "Sistem otomatis mengirim pengingat iuran ke {$terkirim} warga",
            'orange', 'activity', null
        );

        $this->info("Pengingat dikirim ke {$terkirim} warga.");
    }
}
