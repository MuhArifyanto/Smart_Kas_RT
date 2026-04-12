<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class AktivitasSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $warga = User::where('role', 'warga')->first();

        ActivityLog::insert([
            ['user_id' => $admin?->id, 'tipe' => 'pembayaran_disetujui', 'deskripsi' => 'Admin menyetujui pembayaran Ahmad Fadli', 'warna' => 'green', 'icon' => 'check', 'created_at' => now()->subMinutes(30), 'updated_at' => now()],
            ['user_id' => $warga?->id, 'tipe' => 'upload_bukti', 'deskripsi' => 'Dewi Lestari mengunggah bukti pembayaran', 'warna' => 'blue', 'icon' => 'upload', 'created_at' => now()->subHours(2), 'updated_at' => now()],
            ['user_id' => $admin?->id, 'tipe' => 'tambah_warga', 'deskripsi' => 'Admin menambahkan warga baru (Andi Pratama)', 'warna' => 'purple', 'icon' => 'user-plus', 'created_at' => now()->subHours(3), 'updated_at' => now()],
            ['user_id' => $admin?->id, 'tipe' => 'generate_iuran', 'deskripsi' => 'Admin membuat tagihan iuran Maret 2026 untuk 8 warga', 'warna' => 'yellow', 'icon' => 'file', 'created_at' => now()->subHours(5), 'updated_at' => now()],
            ['user_id' => $admin?->id, 'tipe' => 'pembayaran_ditolak', 'deskripsi' => 'Admin menolak pembayaran Rudi Hermawan', 'warna' => 'red', 'icon' => 'x', 'created_at' => now()->subHours(6), 'updated_at' => now()],
            ['user_id' => $warga?->id, 'tipe' => 'upload_bukti', 'deskripsi' => 'Budi Santoso mengunggah bukti pembayaran', 'warna' => 'blue', 'icon' => 'upload', 'created_at' => now()->subHours(8), 'updated_at' => now()],
            ['user_id' => $admin?->id, 'tipe' => 'tambah_pengeluaran', 'deskripsi' => 'Admin menambahkan pengeluaran: Perbaikan jalan RT (Rp 3.500.000)', 'warna' => 'orange', 'icon' => 'money', 'created_at' => now()->subHours(10), 'updated_at' => now()],
        ]);
    }
}
