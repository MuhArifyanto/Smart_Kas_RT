<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WargaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Budi Santoso',   'email' => 'budi@rt.com',   'alamat' => 'Jl. Mawar No. 12',   'no_hp' => '081234567890', 'status' => 'aktif'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@rt.com',   'alamat' => 'Jl. Melati No. 8',   'no_hp' => '082345678901', 'status' => 'aktif'],
            ['name' => 'Ahmad Fadli',    'email' => 'ahmad@rt.com',  'alamat' => 'Jl. Anggrek No. 15', 'no_hp' => '083456789012', 'status' => 'aktif'],
            ['name' => 'Dewi Lestari',   'email' => 'dewi@rt.com',   'alamat' => 'Jl. Kenanga No. 3',  'no_hp' => '084567890123', 'status' => 'aktif'],
            ['name' => 'Rudi Hermawan',  'email' => 'rudi@rt.com',   'alamat' => 'Jl. Dahlia No. 21',  'no_hp' => '085678901234', 'status' => 'nonaktif'],
            ['name' => 'Rina Marlina',   'email' => 'rina@rt.com',   'alamat' => 'Jl. Flamboyan No. 7','no_hp' => '086789012345', 'status' => 'aktif'],
            ['name' => 'Hendra Wijaya',  'email' => 'hendra@rt.com', 'alamat' => 'Jl. Cempaka No. 4',  'no_hp' => '087890123456', 'status' => 'aktif'],
            ['name' => 'Joko Susilo',    'email' => 'joko@rt.com',   'alamat' => 'Jl. Teratai No. 9',  'no_hp' => '088901234567', 'status' => 'nonaktif'],
        ];

        foreach ($data as $d) {
            User::firstOrCreate(['email' => $d['email']], array_merge($d, [
                'password' => Hash::make($d['no_hp']),
                'role'     => 'warga',
            ]));
        }
    }
}
