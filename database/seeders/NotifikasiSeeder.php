<?php

namespace Database\Seeders;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $warga = User::where('role', 'warga')->first();

        if ($admin) {
            Notifikasi::insert([
                ['user_id' => $admin->id, 'judul' => 'Ada 3 bukti pembayaran baru', 'pesan' => 'Budi Santoso, Siti Rahayu, dan Ahmad Fauzi telah mengirim bukti pembayaran iuran bulan Maret.', 'tipe' => 'info', 'created_at' => now()->subMinutes(5), 'updated_at' => now()],
                ['user_id' => $admin->id, 'judul' => '10 warga belum bayar', 'pesan' => 'Terdapat 10 warga yang belum membayar iuran bulan ini. Pertimbangkan untuk mengirim pengingat.', 'tipe' => 'peringatan', 'created_at' => now()->subHour(), 'updated_at' => now()],
                ['user_id' => $admin->id, 'judul' => 'Tagihan bulan ini telah dibuat', 'pesan' => 'Tagihan iuran bulan Maret 2026 sebesar Rp 150.000 telah berhasil dibuat untuk 156 warga.', 'tipe' => 'sukses', 'created_at' => now()->subHours(2), 'updated_at' => now()],
            ]);
        }

        if ($warga) {
            Notifikasi::insert([
                ['user_id' => $warga->id, 'judul' => 'Pengingat Pembayaran Iuran', 'pesan' => 'Iuran bulan Maret 2026 sebesar Rp 150.000 belum dibayar. Harap segera melakukan pembayaran sebelum tanggal 31 Maret 2026.', 'tipe' => 'peringatan', 'created_at' => now()->subHours(1), 'updated_at' => now()],
                ['user_id' => $warga->id, 'judul' => 'Pembayaran Dikonfirmasi', 'pesan' => 'Pembayaran iuran bulan Februari 2026 Anda telah dikonfirmasi. Terima kasih!', 'tipe' => 'sukses', 'created_at' => now()->subDays(2), 'updated_at' => now()],
            ]);
        }
    }
}
