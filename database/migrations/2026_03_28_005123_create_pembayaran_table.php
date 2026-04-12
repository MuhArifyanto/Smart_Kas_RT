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
        // Drop tabel lama yang kosong jika ada
        Schema::dropIfExists('pembayaran');

        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iuran_id')->constrained('iuran')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('metode', ['ewallet', 'qris', 'transfer_bank']);
            $table->string('provider')->nullable(); // dana, gopay, ovo, shopeepay, linkaja, qris, mandiri
            $table->unsignedBigInteger('jumlah');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->string('bukti_bayar')->nullable(); // path file bukti
            $table->string('catatan')->nullable();
            $table->timestamp('dibayar_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
