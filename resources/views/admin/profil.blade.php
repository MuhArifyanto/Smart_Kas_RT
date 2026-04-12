@extends('layouts.admin')
@section('title', 'Profil')
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
            style="{{ auth()->user()->banner_url ? 'background:url('.auth()->user()->banner_url.') center/cover no-repeat;' : 'background:linear-gradient(135deg,#1e3a5f 0%,#1E3A8A 100%);' }}">
        </div>

        {{-- Tombol kamera banner --}}
        <button onclick="document.getElementById('bannerInput').click()" type="button"
            style="position:absolute;top:80px;right:12px;width:32px;height:32px;background:rgba(0,0,0,0.5);border:2px solid rgba(255,255,255,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:30">
            <svg style="width:14px;height:14px" fill="none" stroke="white" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </button>
        <form method="POST" action="{{ route('admin.profil.banner') }}" enctype="multipart/form-data" id="bannerForm" style="display:none">
            @csrf
            <input type="file" id="bannerInput" name="banner" accept="image/*" onchange="uploadBanner(this)">
        </form>

        {{-- Avatar --}}
        <div class="px-5" style="margin-top:-44px;position:relative;z-index:10">
            <div style="position:relative;display:inline-block">
                <div id="avatarPreview"
                    style="width:88px;height:88px;border-radius:50%;border:4px solid white;box-shadow:0 4px 12px rgba(0,0,0,0.15);overflow:hidden;background:linear-gradient(135deg,#60a5fa,#1E3A8A)">
                    @if(auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover" onerror="this.outerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:white;font-size:32px;font-weight:700\'>{{ strtoupper(substr(auth()->user()->name ?? \"A\", 0, 1)) }}</div>'">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:white;font-size:32px;font-weight:700">
                            {{ strtoupper(substr(auth()->user()->name??'A',0,1)) }}
                        </div>
                    @endif
                </div>
                <button onclick="document.getElementById('avatarDirectInput').click()"
                    style="position:absolute;bottom:2px;right:2px;width:26px;height:26px;background:#1E3A8A;border:2px solid white;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer">
                    <svg style="width:12px;height:12px" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </button>
                <form method="POST" action="{{ route('admin.profil.avatar') }}" enctype="multipart/form-data" id="avatarDirectForm" style="display:none">
                    @csrf
                    <input type="file" id="avatarDirectInput" name="avatar" accept="image/*" onchange="uploadAvatar(this)">
                </form>
            </div>
        </div>

        {{-- Info singkat --}}
        <div class="px-5 pt-3 pb-5">
            <h2 class="text-base font-bold text-gray-800">{{ auth()->user()->name }}</h2>
            <p class="text-xs text-gray-400 mt-0.5">Administrator</p>

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
                    {{ auth()->user()->no_hp ?? '-' }}
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ auth()->user()->email }}
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ auth()->user()->alamat ?? 'RT 05 / RW 03' }}
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
                <div class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-700">{{ auth()->user()->name }}</div>
            </div>
            <div>
                <label class="block text-xs text-gray-400 mb-1">Role</label>
                <div class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-700">Administrator</div>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1">Alamat</label>
                <div class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-700">{{ auth()->user()->alamat ?? '-' }}</div>
            </div>
            <div>
                <label class="block text-xs text-gray-400 mb-1">Email</label>
                <div class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-700">{{ auth()->user()->email }}</div>
            </div>
            <div>
                <label class="block text-xs text-gray-400 mb-1">Nomor Telepon</label>
                <div class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-700">{{ auth()->user()->no_hp ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Baris 2: Keamanan Akun --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h3 class="font-semibold text-gray-800 text-sm mb-4">Keamanan Akun</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <button onclick="openModal('modalPassword')"
            class="flex items-center justify-between px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-700 hover:bg-gray-50 transition-colors">
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
        <div class="flex items-center justify-between px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-700">
            <span class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Verifikasi Email
            </span>
            @if(auth()->user()->email_verified_at)
                <span class="text-xs text-green-600 font-medium">Terverifikasi</span>
            @else
                <span class="text-xs text-yellow-600 font-medium">Belum</span>
            @endif
        </div>
    </div>
    <p class="text-xs text-gray-400 mt-4">Terakhir diperbarui: {{ auth()->user()->updated_at->translatedFormat('d F Y, H:i') }} WIB</p>
</div>

{{-- Sistem & Maintenance --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-5">
    <h3 class="font-semibold text-gray-800 text-sm mb-4">Sistem & Maintenance</h3>
    <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl">
        <p class="text-sm text-blue-800 font-bold mb-1">Perbaiki Gambar & Link</p>
        <p class="text-xs text-blue-600 mb-4 leading-relaxed">Gunakan fitur ini jika gambar (profil/banner/pembayaran) tidak muncul di server hosting atau domain utama.</p>
        
        <form action="{{ route('admin.system.fix-storage') }}" method="POST">
            @csrf
            <button type="submit" 
                class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-blue-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                Perbaiki Link Storage & Bersihkan Cache
            </button>
        </form>
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
        <form method="POST" action="{{ route('admin.profil.update') }}" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ auth()->user()->name }}" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ auth()->user()->email }}" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nomor HP</label>
                <input type="text" name="no_hp" value="{{ auth()->user()->no_hp }}"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                <input type="text" name="alamat" value="{{ auth()->user()->alamat }}"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" onclick="closeModal('modalEdit')"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit"
                    class="flex-1 py-2.5 text-white text-sm font-semibold rounded-xl"
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
        <form method="POST" action="{{ route('admin.profil.password') }}" class="px-6 py-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Kata Sandi Baru</label>
                <input type="password" name="password" required minlength="8"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
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

@endsection

@push('scripts')
<script>
function openModal(id) { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
function closeModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }
function uploadBanner(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('bannerDiv').style.background = `url(${e.target.result}) center/cover no-repeat`; };
        reader.readAsDataURL(input.files[0]);
        document.getElementById('bannerForm').submit();
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
