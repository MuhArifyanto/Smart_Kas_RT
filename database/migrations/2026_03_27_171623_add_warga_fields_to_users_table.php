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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->string('alamat')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('no_hp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(array_filter(['alamat', 'no_hp', 'status'], fn($c) => Schema::hasColumn('users', $c)));
        });
    }
};
