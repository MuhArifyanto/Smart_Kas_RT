<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'iuran_id', 'user_id', 'metode', 'provider',
        'jumlah', 'status', 'bukti_bayar', 'catatan', 'dibayar_at',
    ];

    protected $casts = ['dibayar_at' => 'datetime'];

    public function iuran()  { return $this->belongsTo(Iuran::class); }
    public function user()   { return $this->belongsTo(User::class); }

    public function labelMetode(): string
    {
        return match($this->metode) {
            'ewallet'       => 'E-Wallet (' . strtoupper($this->provider) . ')',
            'qris'          => 'QRIS',
            'transfer_bank' => 'Transfer Bank (' . strtoupper($this->provider) . ')',
            default         => '-',
        };
    }

    public function labelStatus(): string
    {
        return match($this->status) {
            'menunggu'  => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            default     => '-',
        };
    }

    public function colorStatus(): string
    {
        return match($this->status) {
            'menunggu'  => 'bg-yellow-100 text-yellow-700',
            'disetujui' => 'bg-green-100 text-green-700',
            'ditolak'   => 'bg-red-100 text-red-600',
            default     => 'bg-gray-100 text-gray-500',
        };
    }
}
