<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pesan extends Model
{
    protected $table = 'pesan';

    protected $fillable = [
        'pengirim_id',
        'penerima_id',
        'isi_pesan',
        'file_path',
        'file_type',
        'dibaca_at',
    ];

    protected $casts = [
        'dibaca_at' => 'datetime',
    ];

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }
}
