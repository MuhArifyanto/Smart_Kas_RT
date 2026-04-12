@extends('layouts.admin')
@section('title', 'Aktivitas')
@section('page-title', 'Aktivitas')

@section('content')

@if(session('success'))
<div class="mb-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between px-6 py-6 border-b border-gray-100 gap-5">
        <div>
            <h3 class="font-bold text-gray-800 text-lg">Log Aktivitas Sistem</h3>
            <p class="text-xs text-gray-400 mt-1">Riwayat semua aktivitas di sistem Smart Kas RT</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            {{-- Info jadwal otomatis --}}
            <div class="hidden sm:flex items-center gap-2 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-3 py-2 rounded-xl border border-emerald-100">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                OTOMATIS AKTIF
            </div>
            {{-- Filter --}}
            <form method="GET" action="{{ route('admin.aktivitas') }}" class="flex-1 sm:flex-none">
                <select name="tipe" onchange="this.form.submit()"
                    class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white font-medium text-gray-700">
                    <option value="">Semua Aktivitas</option>
                    <option value="pembayaran_disetujui" {{ request('tipe') === 'pembayaran_disetujui' ? 'selected' : '' }}>Pembayaran Disetujui</option>
                    <option value="pembayaran_ditolak" {{ request('tipe') === 'pembayaran_ditolak' ? 'selected' : '' }}>Pembayaran Ditolak</option>
                    <option value="upload_bukti" {{ request('tipe') === 'upload_bukti' ? 'selected' : '' }}>Upload Bukti</option>
                    <option value="tambah_warga" {{ request('tipe') === 'tambah_warga' ? 'selected' : '' }}>Tambah Warga</option>
                    <option value="generate_iuran" {{ request('tipe') === 'generate_iuran' ? 'selected' : '' }}>Generate Iuran</option>
                    <option value="tambah_pengeluaran" {{ request('tipe') === 'tambah_pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </form>
            {{-- Hapus semua --}}
            <form method="POST" action="{{ route('admin.aktivitas.hapus') }}"
                onsubmit="return confirm('Hapus semua log aktivitas?')" class="flex-none">
                @csrf @method('DELETE')
                <button type="submit"
                    class="text-xs font-bold text-red-500 hover:text-red-700 px-4 py-2.5 border border-red-200 rounded-xl hover:bg-red-50 transition-all active:scale-95">
                    Hapus Semua
                </button>
            </form>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="px-6 py-4">
        @forelse($logs as $log)
        @php
            $colors = [
                'green'  => ['bg' => 'bg-green-100',  'text' => 'text-green-600',  'border' => 'border-green-200',  'card' => 'bg-green-50/60'],
                'blue'   => ['bg' => 'bg-blue-100',   'text' => 'text-blue-600',   'border' => 'border-blue-200',   'card' => 'bg-blue-50/60'],
                'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'border' => 'border-purple-200', 'card' => 'bg-purple-50/60'],
                'red'    => ['bg' => 'bg-red-100',    'text' => 'text-red-500',    'border' => 'border-red-200',    'card' => 'bg-red-50/60'],
                'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'border' => 'border-yellow-200', 'card' => 'bg-yellow-50/60'],
                'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-500', 'border' => 'border-orange-200', 'card' => 'bg-orange-50/60'],
            ];
            $c = $colors[$log->warna ?? 'blue'] ?? $colors['blue'];

            $icons = [
                'check'     => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'upload'    => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12',
                'user-plus' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
                'user-minus'=> 'M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6',
                'file'      => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'money'     => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
                'x'         => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                'activity'  => 'M13 10V3L4 14h7v7l9-11h-7z',
                'login'     => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1',
            ];
            $iconPath = $icons[$log->icon ?? 'activity'] ?? $icons['activity'];
        @endphp

        <div class="flex gap-3 md:gap-4 mb-4 last:mb-0">
            {{-- Icon --}}
            <div class="flex flex-col items-center flex-shrink-0">
                <div class="w-9 h-9 rounded-full {{ $c['bg'] }} flex items-center justify-center">
                    <svg class="w-4 h-4 {{ $c['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
                    </svg>
                </div>
                @if(!$loop->last)
                <div class="w-px flex-1 bg-gray-200 mt-2 mb-0 min-h-4"></div>
                @endif
            </div>

            {{-- Konten --}}
            <div class="flex-1 pb-4">
                <div class="{{ $c['card'] }} border {{ $c['border'] }} rounded-2xl p-4 shadow-sm transition-all hover:shadow-md">
                    <p class="text-sm text-gray-800 leading-relaxed">
                        {!! strip_tags($log->deskripsi, '<strong><b><a><span>') !!}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $log->created_at->format('H:i') }} WIB &bull;
                        {{ $log->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>
        @empty
        <div class="py-16 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <p class="font-medium">Belum ada aktivitas tercatat</p>
            <p class="text-sm mt-1">Aktivitas akan muncul saat admin atau warga melakukan tindakan</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/40">
        {{ $logs->links('vendor.pagination.simple-tailwind') }}
    </div>
    @endif
</div>

@endsection
