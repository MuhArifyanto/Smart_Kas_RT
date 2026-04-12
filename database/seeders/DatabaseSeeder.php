<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'     => 'Admin RT',
            'email'    => 'admin@rt.com',
            'password' => bcrypt('admin'),
            'role'     => 'admin',
        ]);

        User::factory()->create([
            'name'     => 'Warga RT',
            'email'    => 'warga@rt.com',
            'password' => bcrypt('warga'),
            'role'     => 'warga',
        ]);
    }
}
