<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Services\BackupManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackupDatabase extends Command
{
    protected $signature   = 'backup:database';
    protected $description = 'Buat backup database (.sql.zip) dan hapus file lama (simpan 30 terbaru)';

    public function handle(BackupManager $backup): void
    {
        $this->info('Memulai backup database (PDO + ZipArchive)...');

        try {
            $path = $backup->create();
            $this->info("Backup berhasil: {$path}");

            // Catat ke activity log
            ActivityLog::catat(
                tipe: 'backup_database',
                deskripsi: "Backup database otomatis berhasil dibuat → <strong>" . basename($path) . "</strong>",
                warna: 'blue',
                icon: 'file',
                userId: null
            );
        } catch (\Throwable $e) {
            $this->error("Backup gagal: {$e->getMessage()}");
            Log::error('backup:database command gagal: ' . $e->getMessage());

            ActivityLog::catat(
                tipe: 'backup_database',
                deskripsi: "Backup database otomatis <strong>GAGAL</strong>: {$e->getMessage()}",
                warna: 'red',
                icon: 'x',
                userId: null
            );
            return;
        }

        $backup->prune(30);
        $this->info('Prune selesai — maksimal 30 file backup dipertahankan.');
    }
}
