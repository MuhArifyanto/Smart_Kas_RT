@extends('layouts.admin')
@section('title', 'Iuran')
@section('page-title', 'Iuran')

@section('content')

{{-- Alert --}}
@if(session('success'))
<div class="mb-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Info tagihan --}}
<div class="mb-5 flex items-center justify-between gap-2 p-3.5 bg-blue-50 border border-blue-100 rounded-xl text-sm text-blue-700">
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>Tagihan dibuat otomatis setiap tanggal 1. Pengingat dikirim otomatis tanggal 15 & 25.</span>
    </div>
    {{-- Tombol trigger manual jika perlu --}}
    <form method="POST" action="{{ route('admin.iuran.generate') }}" class="flex-shrink-0">
        @csrf
        <input type="hidden" name="bulan" value="{{ now()->format('Y-m') }}">
        <input type="hidden" name="nominal" value="150000">
        <button type="submit" class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 transition-colors whitespace-nowrap">
            Generate Sekarang
        </button>
    </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center justify-between sm:block">
        <p class="text-xs sm:text-sm text-gray-500 mb-0 sm:mb-1 uppercase font-bold sm:normal-case">Lunas</p>
        <p class="text-2xl sm:text-3xl font-bold text-green-600 leading-none">{{ $summary['lunas'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center justify-between sm:block">
        <p class="text-xs sm:text-sm text-gray-500 mb-0 sm:mb-1 uppercase font-bold sm:normal-case">Menunggu</p>
        <p class="text-2xl sm:text-3xl font-bold text-yellow-500 leading-none">{{ $summary['menunggu'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center justify-between sm:block">
        <p class="text-xs sm:text-sm text-gray-500 mb-0 sm:mb-1 uppercase font-bold sm:normal-case">Belum Bayar</p>
        <p class="text-2xl sm:text-3xl font-bold text-red-500 leading-none">{{ $summary['belum_bayar'] }}</p>
    </div>
</div>

{{-- Toolbar --}}
<div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route('admin.iuran') }}" class="flex flex-wrap items-center gap-2">
        {{-- Search --}}
        <div class="relative flex-1 sm:flex-initial min-w-[200px]">
            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama warga..."
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
        </div>
        {{-- Filter bulan --}}
        <input type="month" name="bulan" value="{{ $bulan }}"
            class="flex-1 sm:flex-initial px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white min-w-[140px]">
        {{-- Filter status --}}
        <select name="status" class="flex-1 sm:flex-initial px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            <option value="">Semua Status</option>
            <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="belum_bayar" {{ request('status') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
        </select>
        <button type="submit" class="flex-1 sm:flex-initial px-4 py-2.5 bg-blue-50 text-blue-600 hover:bg-blue-100 text-sm font-bold rounded-xl transition-colors">
            Filter
        </button>
    </form>

    {{-- Generate Tagihan --}}
    <form method="POST" action="{{ route('admin.notifikasi.pengingat') }}" class="flex-shrink-0">
        @csrf
        <input type="hidden" name="pesan" value="Iuran bulan {{ now()->translatedFormat('F Y') }} belum dibayar. Segera lakukan pembayaran sebelum tanggal 28.">
        <button type="submit"
            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Kirim Pengingat
        </button>
    </form>
</div>

{{-- Tabel & Card View --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    {{-- Desktop Table --}}
    <table class="w-full text-sm hidden sm:table">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50/60">
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Warga</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Bulan</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nominal</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($iuran as $item)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($item->user->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $item->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $item->user->alamat ?? '' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 text-gray-600">
                    {{ \Carbon\Carbon::parse($item->bulan . '-01')->translatedFormat('F Y') }}
                </td>
                <td class="px-5 py-4 font-medium text-gray-800">
                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                </td>
                <td class="px-5 py-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full {{ $item->colorStatus() }}">
                        <span class="w-1.5 h-1.5 rounded-full
                            @if($item->status === 'lunas') bg-green-500
                            @elseif($item->status === 'menunggu') bg-yellow-500
                            @else bg-red-500 @endif">
                        </span>
                        {{ $item->labelStatus() }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center justify-end gap-1">
                        {{-- Hapus --}}
                        <form method="POST" action="{{ route('admin.iuran.destroy', $item) }}"
                            onsubmit="return confirm('Hapus data iuran ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="font-medium">Belum ada data iuran</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Mobile Card View --}}
    <div class="sm:hidden divide-y divide-gray-50">
        @forelse($iuran as $item)
        <div class="p-4 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-base flex-shrink-0">
                        {{ strtoupper(substr($item->user->name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800 leading-tight">{{ $item->user->name ?? '-' }}</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider mt-0.5">{{ \Carbon\Carbon::parse($item->bulan . '-01')->translatedFormat('F Y') }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $item->colorStatus() }}">
                    <span class="w-1 h-1 rounded-full
                        @if($item->status === 'lunas') bg-green-500
                        @elseif($item->status === 'menunggu') bg-yellow-500
                        @else bg-red-500 @endif">
                    </span>
                    {{ $item->labelStatus() }}
                </span>
            </div>

            <div class="flex items-center justify-between bg-gray-50/50 p-3 rounded-xl">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-0.5 tracking-tighter">Nominal Tagihan</p>
                    <p class="text-sm font-black text-gray-800 tracking-tight">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                </div>
                <div class="flex items-center gap-1">
                    <form method="POST" action="{{ route('admin.iuran.destroy', $item) }}"
                        onsubmit="return confirm('Hapus data iuran ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-red-400 hover:bg-red-50 rounded-lg transition-colors active:scale-95">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-gray-400">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="font-medium text-sm">Belum ada data iuran</p>
        </div>
        @endforelse
    </div>

    <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between bg-gray-50/40">
        <p class="text-xs text-gray-400">
            Menampilkan {{ $iuran->firstItem() ?? 0 }}–{{ $iuran->lastItem() ?? 0 }} dari {{ $iuran->total() }} data
        </p>
        {{ $iuran->links('vendor.pagination.simple-tailwind') }}
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) {
    const m = document.getElementById(id);
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function closeModal(id) {
    const m = document.getElementById(id);
    m.classList.add('hidden');
    m.classList.remove('flex');
}
</script>
@endpush
