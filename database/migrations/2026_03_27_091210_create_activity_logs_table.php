<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('tipe'); // pembayaran_disetujui, pembayaran_ditolak, upload_bukti, tambah_warga, hapus_warga, generate_iuran, tambah_pengeluaran, login, dll
            $table->string('deskripsi');
            $table->string('warna')->default('blue'); // green, blue, purple, red, yellow, orange
            $table->string('icon')->default('activity'); // activity, check, upload, user-plus, user-minus, file, money, login
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
