@extends('layouts.warga')
@section('title', 'Profil Saya')
@section('page-title', 'Profil')

@section('content')

@if(session('success'))
<div class="mb-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">{{ $errors->first() }}</div>
@endif

{{-- Header --}}
<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-800">Profil Saya</h1>
    <p class="text-sm text-gray-400 mt-0.5">Kelola informasi profil Anda</p>
</div>

{{-- Baris 1: Card Foto + Informasi Personal --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

    {{-- Kolom Kiri: Card Foto Profil --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" style="position:relative">

        {{-- Banner --}}
        <div id="bannerDiv" class="h-28 w-full"
            style="{{ $user->banner_url ? 'background:url('.$user->banner_url.') center/cover no-repeat;' : 'background:linear-gradient(135deg,#064e3b 0%,#059669 100%);' }}">
        </div>

        {{-- Tombol kamera banner --}}
        <button onclick="document.getElementById('bannerInput').click()" type="button"
            style="position:absolute;top:80px;right:12px;width:32px;height:32px;background:rgba(0,0,0,0.5);border:2px solid rgba(255,255,255,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:30">
            <svg style="width:14px;height:14px" fill="none" stroke="white" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </button>
        <form method="POST" action="{{ route('warga.profil.banner') }}" enctype="multipart/form-data" id="bannerForm" style="display:none">
            @csrf
            <input type="file" id="bannerInput" name="banner" accept="image/*"
                onchange="previewBanner(this); document.getElementById('bannerForm').submit()">
        </form>

        {{-- Avatar --}}
        <div class="px-5" style="margin-top:-44px;position:relative;z-index:10">
            <div style="position:relative;display:inline-block">
                <div id="avatarPreview"
                    style="width:88px;height:88px;border-radius:50%;border:4px solid white;box-shadow:0 4px 12px rgba(0,0,0,0.15);overflow:hidden;background:linear-gradient(135deg,#34d399,#059669)">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover" onerror="this.outerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:white;font-size:32px;font-weight:700\'>{{ strtoupper(substr($user->name, 0, 1)) }}</div>'">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:white;font-size:32px;font-weight:700">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                    @endif
                </div>
                <button onclick="document.getElementById('avatarDirectInput').click()"
                    style="position:absolute;bottom:2px;right:2px;width:26px;height:26px;background:#059669;border:2px solid white;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer">
                    <svg style="width:12px;height:12px" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </button>
                <form method="POST" action="{{ route('warga.profil.avatar') }}" enctype="multipart/form-data" id="avatarDirectForm" style="display:none">
                    @csrf
                    <input type="file" id="avatarDirectInput" name="avatar" accept="image/*" onchange="uploadAvatar(this)">
                </form>
            </div>
        </div>

        {{-- Info singkat --}}
        <div class="px-5 pt-3 pb-5">
            <h2 class="text-base font-bold text-gray-800">{{ $user->name }}</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $user->alamat ?? 'Alamat belum diisi' }}</p>

            <button onclick="document.getElementById('avatarDirectInput').click()"
                class="mt-3 w-full flex items-center justify-center gap-2 py-2 border border-gray-200 rounded-xl text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Ubah Foto Profil
            </button>

            <div class="mt-4 space-y-2 border-t border-gray-100 pt-4">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ $user->no_hp ?? '-' }}
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ $user->email }}
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    RT 05 / RW 03
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Informasi Personal --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-semibold text-gray-800 text-sm">Informasi Personal</h3>
            <button onclick="openModal('modalEdit')"
                class="flex items-center gap-2 px-4 py-2 text-white text-xs font-semibold rounded-xl transition-colors"
                style="background:#1E3A8A">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Profil
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-400 mb-1">Nama Lengkap</label>
                <div class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-700">{{ $user->name }}</div>
            </div>
            <div>
                <label class="block text-xs text-gray-400 mb-1">Nomor Rumah</label>
                <div class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-700">{{ $user->no_rumah ?? '-' }}</div>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1">Alamat Lengkap</label>
                <div class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-700">{{ $user->alamat ?? '-' }}</div>
            </div>
            <div>
                <label class="block text-xs text-gray-400 mb-1">Email</label>
                <div class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-700">{{ $user->email }}</div>
            </div>
            <div>
                <label class="block text-xs text-gray-400 mb-1">Nomor Telepon</label>
                <div class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-700">{{ $user->no_hp ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Baris 2: Status Keanggotaan + Keamanan Akun --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

    {{-- Status Keanggotaan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 text-sm mb-4">Status Keanggotaan</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                <span class="text-sm text-gray-500">Status</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                    Aktif
                </span>
            </div>
            <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                <span class="text-sm text-gray-500">Bergabung Sejak</span>
                <span class="text-sm font-semibold text-gray-800">{{ $user->created_at->translatedFormat('F Y') }}</span>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <span class="text-sm text-gray-500">Iuran Terakhir</span>
                @php
                    $iuranTerakhir = \App\Models\Iuran::where('user_id', $user->id)->where('status','lunas')->latest('dibayar_at')->first();
                @endphp
                <span class="text-sm font-semibold text-gray-800">
                    {{ $iuranTerakhir ? \Carbon\Carbon::parse($iuranTerakhir->bulan.'-01')->translatedFormat('F Y') : '-' }}
                </span>
            </div>
        </div>

        {{-- Progress pembayaran --}}
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-400">Kepatuhan Pembayaran</span>
                <span class="text-xs font-bold {{ $persen >= 80 ? 'text-green-600' : ($persen >= 50 ? 'text-yellow-600' : 'text-red-500') }}">{{ $persen }}%</span>
            </div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div id="progressBar"
                    class="h-full rounded-full transition-all duration-1000 {{ $persen >= 80 ? 'bg-green-500' : ($persen >= 50 ? 'bg-yellow-400' : 'bg-red-400') }}"
                    style="width:0%" data-target="{{ $persen }}"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1.5">{{ $totalLunas }} dari {{ $totalIuran }} bulan telah dibayar</p>
        </div>
    </div>

    {{-- Keamanan Akun --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 text-sm mb-4">Keamanan Akun</h3>
        <div class="space-y-3">
            <button onclick="openModal('modalPassword')"
                class="w-full flex items-center justify-between px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <span class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Ubah Password
                </span>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div class="w-full flex items-center justify-between px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-700">
                <span class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Verifikasi Email
                </span>
                @if($user->email_verified_at)
                    <span class="text-xs text-green-600 font-medium">Terverifikasi</span>
                @else
                    <span class="text-xs text-yellow-600 font-medium">Belum</span>
                @endif
            </div>
            @php $isVerified = !empty($user->phone_verified_at); @endphp
            <div class="w-full flex items-center justify-between px-4 py-3 border {{ $isVerified ? 'border-emerald-200 bg-emerald-50/30' : 'border-gray-200 bg-white' }} rounded-xl text-sm transition-all">
                <span class="flex items-center gap-2.5 {{ $isVerified ? 'text-emerald-700' : 'text-gray-700' }}">
                    <svg class="w-4 h-4 {{ $isVerified ? 'text-emerald-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Verifikasi Nomor Telepon
                </span>
                @if($isVerified)
                    <span class="flex items-center gap-1.5 text-xs font-semibold text-emerald-600">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Terverifikasi
                    </span>
                @else
                    <button type="button" onclick="openVerifyModal()" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 underline focus:outline-none">
                        Verifikasi Sekarang
                    </button>
                @endif
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-4">Terakhir diperbarui: {{ $user->updated_at->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>
</div>

{{-- Butuh Bantuan --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h3 class="font-semibold text-gray-800 text-sm mb-3">Butuh Bantuan?</h3>
    <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl">
        <p class="text-sm text-gray-600 mb-4">Jika ada pertanyaan atau kendala terkait akun Anda, silakan hubungi pengurus RT:</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-bold text-gray-800">Ketua RT 05</p>
                <p class="text-sm text-gray-600 mt-0.5">Pak Hendra</p>
                <p class="text-sm text-gray-600">0812-9999-8888</p>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800">Bendahara RT 05</p>
                <p class="text-sm text-gray-600 mt-0.5">Ibu Siti</p>
                <p class="text-sm text-gray-600">0813-7777-6666</p>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Profil --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Edit Profil</h3>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('warga.profil.update') }}" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ $user->name }}" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nomor HP</label>
                <input type="text" name="no_hp" value="{{ $user->no_hp }}"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                <input type="text" name="alamat" value="{{ $user->alamat }}"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" onclick="closeModal('modalEdit')"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit"
                    class="flex-1 py-2.5 text-white text-sm font-semibold rounded-xl transition-colors"
                    style="background:#1E3A8A">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Ganti Password --}}
<div id="modalPassword" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalPassword')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Ganti Kata Sandi</h3>
            <button onclick="closeModal('modalPassword')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('warga.profil.password') }}" class="px-6 py-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Kata Sandi Baru</label>
                <input type="password" name="password" required minlength="8"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" onclick="closeModal('modalPassword')"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit"
                    class="flex-1 py-2.5 text-white text-sm font-semibold rounded-xl"
                    style="background:#1E3A8A">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('modals')
{{-- Modal Verifikasi Telepon --}}
<div id="verifyModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="verifyContent">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-emerald-50/50">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Verifikasi Nomor HP
            </h3>
            <button onclick="closeVerifyModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6">
            {{-- Step 1: Input Nomor HP --}}
            <div id="stepInputPhone">
                <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                    Kami akan mengirimkan kode OTP melalui SMS untuk memastikan nomor HP Anda aktif.
                </p>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nomor Telepon (WhatsApp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-medium">+62</span>
                        <input type="text" id="phoneVerifyInput" value="{{ ltrim($user->no_hp, '0') }}" 
                               class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-medium"
                               placeholder="812xxxxxx">
                    </div>
                </div>
                <div id="recaptcha-container" class="mb-4 flex justify-center"></div>
                <button type="button" onclick="sendOTP()" id="btnSendOTP"
                        class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-emerald-200 transition-all flex items-center justify-center gap-2">
                    Kirim Kode OTP
                </button>
            </div>

            {{-- Step 2: Input OTP --}}
            <div id="stepInputOTP" class="hidden text-center">
                <p class="text-sm text-gray-600 mb-6">
                    Masukkan 6 digit kode yang dikirim ke <br>
                    <span class="font-bold text-gray-800" id="displayPhone"></span>
                </p>
                <div class="flex justify-center gap-2 mb-6">
                    <input type="text" maxlength="6" id="otpInput" 
                           class="w-48 text-center tracking-[1rem] text-2xl font-bold py-3 bg-gray-50 border-2 border-emerald-100 rounded-xl focus:border-emerald-500 focus:ring-0 transition-all"
                           placeholder="------">
                </div>
                <button type="button" onclick="verifyOTP()" id="btnVerifyOTP"
                        class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-emerald-200 transition-all">
                    Verifikasi Sekarang
                </button>
                <button type="button" onclick="resetVerify()" class="mt-4 text-xs text-gray-400 hover:text-emerald-600 transition-colors">
                    Salah nomor? Ganti nomor HP
                </button>
            </div>

            {{-- Loading State --}}
            <div id="verifyLoading" class="hidden flex flex-col items-center py-8">
                <div class="w-12 h-12 border-4 border-emerald-100 border-t-emerald-600 rounded-full animate-spin mb-4"></div>
                <p class="text-sm text-gray-500" id="loadingText">Memproses...</p>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
{{-- Firebase SDK --}}
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-auth-compat.js"></script>

<script>
// Firebase Config
const firebaseConfig = {
    apiKey: "{{ config('services.firebase.api_key') }}",
    authDomain: "{{ config('services.firebase.auth_domain') }}",
    projectId: "{{ config('services.firebase.project_id') }}",
    storageBucket: "{{ config('services.firebase.storage_bucket') }}",
    messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
    appId: "{{ config('services.firebase.app_id') }}"
};

// Initialize Firebase
if (!firebase.apps.length) {
    firebase.initializeApp(firebaseConfig);
}

const auth = firebase.auth();
let confirmationResult = null;

function openVerifyModal() {
    const modal = document.getElementById('verifyModal');
    const content = document.getElementById('verifyContent');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);

    // Init Recaptcha
    if (!window.recaptchaVerifier) {
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            'size': 'normal',
            'callback': (response) => {
                // Success
            }
        });
    }
}

function closeVerifyModal() {
    const modal = document.getElementById('verifyModal');
    const content = document.getElementById('verifyContent');
    content.classList.add('scale-95', 'opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

function resetVerify() {
    document.getElementById('stepInputPhone').classList.remove('hidden');
    document.getElementById('stepInputOTP').classList.add('hidden');
    document.getElementById('verifyLoading').classList.add('hidden');
    if (window.recaptchaVerifier) {
        window.recaptchaVerifier.render().then(widgetId => {
            grecaptcha.reset(widgetId);
        });
    }
}

async function sendOTP() {
    const phone = document.getElementById('phoneVerifyInput').value;
    if (!phone) return alert('Masukkan nomor HP Anda');
    
    const fullPhone = '+62' + phone;
    document.getElementById('displayPhone').innerText = fullPhone;
    
    // Show loading
    document.getElementById('stepInputPhone').classList.add('hidden');
    document.getElementById('verifyLoading').classList.remove('hidden');
    document.getElementById('loadingText').innerText = 'Mengirim SMS OTP...';

    auth.signInWithPhoneNumber(fullPhone, window.recaptchaVerifier)
        .then((result) => {
            confirmationResult = result;
            document.getElementById('verifyLoading').classList.add('hidden');
            document.getElementById('stepInputOTP').classList.remove('hidden');
        }).catch((error) => {
            console.error(error);
            alert('Gagal mengirim SMS: ' + error.message);
            resetVerify();
        });
}

async function verifyOTP() {
    const code = document.getElementById('otpInput').value;
    if (code.length !== 6) return alert('Masukkan 6 digit kode OTP');

    document.getElementById('stepInputOTP').classList.add('hidden');
    document.getElementById('verifyLoading').classList.remove('hidden');
    document.getElementById('loadingText').innerText = 'Memverifikasi kode...';

    confirmationResult.confirm(code).then(async (result) => {
        const user = result.user;
        const idToken = await user.getIdToken();
        const noHp = '0' + document.getElementById('phoneVerifyInput').value;

        // Kirim ke backend Laravel
        const response = await fetch("{{ route('auth.verify-phone') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id_token: idToken,
                no_hp: noHp
            })
        });

        const data = await response.json();
        if (data.status === 'success') {
            alert('Berhasil! Nomor Anda telah terverifikasi.');
            location.reload();
        } else {
            alert('Error: ' + data.error);
            resetVerify();
        }
    }).catch((error) => {
        alert('Kode OTP salah atau sudah kedaluwarsa.');
        resetVerify();
    });
}
</script>
@endpush
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const bar = document.getElementById('progressBar');
    if (bar) setTimeout(() => { bar.style.width = bar.dataset.target + '%'; }, 300);
});
function openModal(id) { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
function closeModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }
function previewBanner(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('bannerDiv').style.background = `url(${e.target.result}) center/cover no-repeat`; };
        reader.readAsDataURL(input.files[0]);
    }
}
function uploadAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('avatarPreview').innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
        };
        reader.readAsDataURL(input.files[0]);
        document.getElementById('avatarDirectForm').submit();
    }
}
</script>
@endpush
