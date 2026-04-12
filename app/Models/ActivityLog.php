<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = ['user_id', 'tipe', 'deskripsi', 'warna', 'icon'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper statis untuk catat aktivitas
    public static function catat(string $tipe, string $deskripsi, string $warna = 'blue', string $icon = 'activity', ?int $userId = null): void
    {
        static::create([
            'user_id'   => $userId ?? auth()->id(),
            'tipe'      => $tipe,
            'deskripsi' => $deskripsi,
            'warna'     => $warna,
            'icon'      => $icon,
        ]);
    }
}
