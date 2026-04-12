<?php

namespace App\Console\Commands;

use App\Models\Iuran;
use App\Models\Notifikasi;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Console\Command;

class GenerateTagihanBulanan extends Command
{
    protected $signature   = 'iuran:generate {--bulan=} {--nominal=150000}';
    protected $description = 'Generate tagihan iuran otomatis untuk semua warga aktif';

    public function handle(): void
    {
        $bulan   = $this->option('bulan') ?? now()->format('Y-m');
        $nominal = (int) $this->option('nominal');

        $warga  = User::where('role', 'warga')->where('status', 'aktif')->get();
        $dibuat = 0;

        foreach ($warga as $w) {
            if (Iuran::where('user_id', $w->id)->where('bulan', $bulan)->exists()) {
                continue;
            }

            Iuran::create([
                'user_id' => $w->id,
                'bulan'   => $bulan,
                'nominal' => $nominal,
                'status'  => 'belum_bayar',
            ]);

            Notifikasi::create([
                'user_id' => $w->id,
                'judul'   => 'Tagihan Iuran ' . \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y'),
                'pesan'   => 'Tagihan iuran bulan ' . \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') .
                             ' sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' telah dibuat. Harap segera melakukan pembayaran.',
                'tipe'    => 'peringatan',
            ]);

            $dibuat++;
        }

        ActivityLog::catat(
            'generate_iuran',
            "Sistem otomatis membuat tagihan iuran {$bulan} untuk {$dibuat} warga",
            'yellow', 'file', null
        );

        $this->info("Tagihan berhasil dibuat untuk {$dibuat} warga bulan {$bulan}.");
    }
}
