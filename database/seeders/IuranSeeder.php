<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class IuranSeeder extends Seeder
{
    public function run(): void
    {
        // Buat tabel jika belum ada dengan struktur benar
        if (!DB::getSchemaBuilder()->hasTable('iuran')) {
            DB::statement("
                CREATE TABLE iuran (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    user_id BIGINT UNSIGNED NOT NULL,
                    bulan VARCHAR(7) NOT NULL,
                    nominal BIGINT UNSIGNED DEFAULT 150000,
                    status ENUM('lunas','menunggu','belum_bayar') DEFAULT 'belum_bayar',
                    dibayar_at TIMESTAMP NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )
            ");
        }

        $warga = User::where('role', 'warga')->get();
        $bulan = now()->format('Y-m');
        $statuses = ['lunas', 'lunas', 'lunas', 'lunas', 'menunggu', 'menunggu', 'belum_bayar', 'belum_bayar'];

        foreach ($warga as $i => $w) {
            $status = $statuses[$i % count($statuses)];
            DB::table('iuran')->updateOrInsert(
                ['user_id' => $w->id, 'bulan' => $bulan],
                [
                    'nominal'    => 150000,
                    'status'     => $status,
                    'dibayar_at' => $status === 'lunas' ? now() : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
