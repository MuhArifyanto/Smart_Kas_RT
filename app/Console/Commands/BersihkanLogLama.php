<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

class BersihkanLogLama extends Command
{
    protected $signature   = 'log:bersihkan {--hari=90}';
    protected $description = 'Hapus log aktivitas yang lebih dari N hari (default 90 hari)';

    public function handle(): void
    {
        $hari    = (int) $this->option('hari');
        $dihapus = ActivityLog::where('created_at', '<', now()->subDays($hari))->delete();

        $this->info("Berhasil menghapus {$dihapus} log aktivitas yang lebih dari {$hari} hari.");
    }
}
