<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{
    protected $table = 'iuran';

    protected $fillable = ['user_id', 'bulan', 'nominal', 'status', 'dibayar_at'];

    protected $casts = ['dibayar_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(\App\Models\Pembayaran::class)->latestOfMany();
    }

    public function labelStatus(): string
    {
        return match($this->status) {
            'lunas'       => 'Lunas',
            'menunggu'    => 'Menunggu',
            'belum_bayar' => 'Belum Bayar',
            default       => '-',
        };
    }

    public function colorStatus(): string
    {
        return match($this->status) {
            'lunas'       => 'bg-green-100 text-green-700',
            'menunggu'    => 'bg-yellow-100 text-yellow-700',
            'belum_bayar' => 'bg-red-100 text-red-600',
            default       => 'bg-gray-100 text-gray-500',
        };
    }
}
