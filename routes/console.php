<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ===== JADWAL OTOMATIS =====

// Generate tagihan iuran setiap tanggal 1 jam 07:00
Schedule::command('iuran:generate')->monthlyOn(1, '07:00')
    ->description('Generate tagihan iuran bulanan otomatis');

// Kirim pengingat ke warga belum bayar: setiap tanggal 15 dan 25 jam 08:00
Schedule::command('iuran:pengingat')->twiceMonthly(15, 25, '08:00')
    ->description('Kirim notifikasi pengingat iuran belum bayar');

// Bersihkan log aktivitas lebih dari 90 hari: setiap minggu Senin jam 02:00
Schedule::command('log:bersihkan')->weekly()->mondays()->at('02:00')
    ->description('Bersihkan log aktivitas lama');

// Backup database otomatis setiap hari jam 02:00
Schedule::command('backup:database')->dailyAt('02:00')
    ->description('Backup database harian otomatis');
