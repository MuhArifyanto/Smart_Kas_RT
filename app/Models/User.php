<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'role',
        'google_id', 'avatar', 'banner', 'alamat', 'no_hp', 'status',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isOnline(): bool
    {
        if (!$this->last_seen_at) return false;
        // User is considered online if seen within last 2 minutes
        return $this->last_seen_at->gt(now()->subMinutes(2));
    }

    public function lastSeenStatus(): string
    {
        if ($this->isOnline()) return "Online";
        if (!$this->last_seen_at) return "Sangat Jarang Aktif";
        
        $diff = $this->last_seen_at->diffInMinutes(now());
        if ($diff < 60) return "Aktif " . $diff . " menit yang lalu";
        
        $diffH = $this->last_seen_at->diffInHours(now());
        if ($diffH < 24) return "Aktif " . $diffH . " jam yang lalu";
        
        return "Aktif " . $this->last_seen_at->translatedFormat('d M Y, H:i');
    }

    public function getAvatarUrlAttribute(): string
    {
        if (!$this->avatar) return "";
        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }
        return '/storage/' . $this->avatar;
    }

    public function getBannerUrlAttribute(): string
    {
        if (!$this->banner) return "";
        if (str_starts_with($this->banner, 'http')) {
            return $this->banner;
        }
        return '/storage/' . $this->banner;
    }

    public function pesanTerkirim(): HasMany
    {
        return $this->hasMany(Pesan::class, 'pengirim_id');
    }

    public function pesanDiterima(): HasMany
    {
        return $this->hasMany(Pesan::class, 'penerima_id');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function notifikasiTerbaru()
    {
        return $this->hasMany(Notifikasi::class)->latest()->limit(10);
    }

    public function jumlahNotifikasiBelumDibaca(): int
    {
        return $this->notifikasi()->whereNull('dibaca_at')->count();
    }
}
