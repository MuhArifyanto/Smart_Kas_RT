@extends('layouts.warga')
@section('title', 'Tagihan Iuran')
@section('page-title', 'Tagihan Iuran')

@section('content')

{{-- Info jatuh tempo --}}
<div class="mb-5 flex items-center gap-3 p-4 bg-blue-50 border border-blue-100 rounded-xl text-sm text-blue-700">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Iuran dibayarkan setiap bulan. Anda bisa membayar kapan saja melalui metode yang tersedia.</span>
</div>

{{-- Metode Pembayaran Tersedia --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5">
    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Metode Pembayaran Tersedia</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="flex items-center gap-3 p-3 bg-blue-50/50 rounded-2xl border border-blue-100 transition-all hover:bg-blue-50">
            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="5" y="6" width="26" height="17" rx="3.5" fill="#93C5FD" transform="rotate(-6 5 6)"/>
                    <rect x="3" y="15" width="34" height="22" rx="5" fill="url(#ewIuran)"/>
                    <ellipse cx="14" cy="20" rx="9" ry="3.5" fill="white" fill-opacity="0.2"/>
                    <path d="M20 21l1.8 3.6 4 .6-2.9 2.8.7 4-3.6-1.9-3.6 1.9.7-4-2.9-2.8 4-.6z" fill="white"/>
                    <defs>
                        <linearGradient id="ewIuran" x1="3" y1="15" x2="37" y2="37" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#38BDF8"/>
                            <stop offset="100%" stop-color="#0369A1"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800">E-Wallet</p>
                <p class="text-[10px] text-gray-400 uppercase font-medium">OVO, DANA, GoPay</p>
            </div>
        </div>
        <div class="flex items-center gap-3 p-3 bg-purple-50/50 rounded-2xl border border-purple-100 transition-all hover:bg-purple-50">
            <div class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg"
                    alt="QRIS" class="w-8 object-contain">
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800">QRIS</p>
                <p class="text-[10px] text-gray-400 uppercase font-medium">Scan & bayar</p>
            </div>
        </div>
        <div class="flex items-center gap-3 p-3 bg-green-50/50 rounded-2xl border border-green-100 transition-all hover:bg-green-50">
            <div class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg"
                    alt="Mandiri" class="w-8 object-contain">
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800">Transfer Bank</p>
                <p class="text-[10px] text-gray-400 uppercase font-medium">Mandiri — Manual</p>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Tagihan & Card View --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
        <h3 class="font-bold text-gray-800 text-sm">Daftar Tagihan Iuran</h3>
        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full uppercase">{{ $iuran->total() }} record</span>
    </div>

    {{-- Desktop Table View --}}
    <table class="w-full text-sm hidden lg:table">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50/60">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Bulan</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Jatuh Tempo</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nominal</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Metode Bayar</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($iuran as $item)
            @php
                $jatuhTempo = \Carbon\Carbon::parse($item->bulan . '-28');
                $terlambat  = $item->status !== 'lunas' && now()->gt($jatuhTempo);
            @endphp
            <tr class="hover:bg-gray-50/50 transition-colors {{ $terlambat ? 'bg-red-50/30' : '' }}">
                <td class="px-5 py-4">
                    <p class="font-semibold text-gray-800">
                        {{ \Carbon\Carbon::parse($item->bulan.'-01')->translatedFormat('F Y') }}
                    </p>
                    @if($terlambat)
                        <p class="text-[10px] text-red-500 font-bold mt-0.5">⚠ Terlambat</p>
                    @endif
                </td>
                <td class="px-5 py-4">
                    <p class="text-gray-700">{{ $jatuhTempo->format('d M Y') }}</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">
                        @if($item->status === 'lunas')
                            <span class="text-green-600">Sudah dibayar</span>
                        @elseif(now()->lt($jatuhTempo))
                            {{ $jatuhTempo->diffForHumans() }}
                        @else
                            <span class="text-red-500">{{ $jatuhTempo->diffForHumans() }}</span>
                        @endif
                    </p>
                </td>
                <td class="px-5 py-4 font-semibold text-gray-800">
                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                </td>
                <td class="px-5 py-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full {{ $item->colorStatus() }}">
                        <span class="w-1.5 h-1.5 rounded-full
                            @if($item->status==='lunas') bg-green-500
                            @elseif($item->status==='menunggu') bg-yellow-500
                            @else bg-red-500 @endif"></span>
                        {{ $item->labelStatus() }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    @if($item->status === 'lunas' && $item->pembayaran)
                        <span class="text-xs text-gray-600">{{ $item->pembayaran->labelMetode() }}</span>
                    @else
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-bold uppercase">E-Wallet</span>
                            <span class="text-[10px] bg-purple-50 text-purple-600 px-2 py-0.5 rounded-full font-bold uppercase">QRIS</span>
                            <span class="text-[10px] bg-green-50 text-green-700 px-2 py-0.5 rounded-full font-bold uppercase">Mandiri</span>
                        </div>
                    @endif
                </td>
                <td class="px-5 py-4 text-right">
                    @if($item->status === 'lunas')
                        <span class="inline-flex items-center gap-1 text-xs text-green-600 font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                            Lunas
                        </span>
                    @elseif($item->status === 'menunggu')
                        <span class="text-xs text-yellow-600 font-bold animate-pulse">Proses...</span>
                    @else
                        <a href="{{ route('bayar', ['iuran_id' => $item->id]) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition-all active:scale-95 shadow-sm shadow-emerald-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Bayar
                        </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center text-gray-400 font-medium">Belum ada tagihan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Mobile Card View --}}
    <div class="lg:hidden divide-y divide-gray-50">
        @forelse($iuran as $item)
        @php
            $jatuhTempo = \Carbon\Carbon::parse($item->bulan . '-28');
            $terlambat  = $item->status !== 'lunas' && now()->gt($jatuhTempo);
        @endphp
        <div class="p-4 space-y-4 {{ $terlambat ? 'bg-red-50/20' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-bold text-gray-800 text-base leading-tight">
                        {{ \Carbon\Carbon::parse($item->bulan.'-01')->translatedFormat('F Y') }}
                    </p>
                    @if($terlambat)
                        <p class="text-[10px] text-red-600 font-black uppercase tracking-wider mt-0.5">⚠ Terlambat Bayar</p>
                    @endif
                </div>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold uppercase rounded-full {{ $item->colorStatus() }}">
                    {{ $item->labelStatus() }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-3 bg-gray-50/50 p-3 rounded-2xl border border-gray-100">
                <div>
                    <p class="text-[9px] text-gray-400 uppercase font-black mb-1">Jatuh Tempo</p>
                    <p class="text-xs text-gray-700 font-bold">{{ $jatuhTempo->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 uppercase font-black mb-1">Total Tagihan</p>
                    <p class="text-sm text-gray-900 font-black tracking-tight">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between gap-4 pt-1">
                <div class="flex-1">
                    <p class="text-[9px] text-gray-400 uppercase font-bold mb-1">Metode Bayar</p>
                    @if($item->status === 'lunas' && $item->pembayaran)
                        <span class="text-xs text-gray-600 font-bold">{{ $item->pembayaran->labelMetode() }}</span>
                    @else
                        <div class="flex items-center gap-1">
                            <span class="text-[9px] bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded-md font-bold uppercase">EW</span>
                            <span class="text-[9px] bg-purple-50 text-purple-600 px-1.5 py-0.5 rounded-md font-bold uppercase">QR</span>
                            <span class="text-[9px] bg-green-50 text-green-700 px-1.5 py-0.5 rounded-md font-bold uppercase">TR</span>
                        </div>
                    @endif
                </div>

                @if($item->status === 'lunas')
                    <div class="flex items-center gap-1 text-xs text-green-600 font-black bg-green-50 px-3 py-2 rounded-xl">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                        Lunas
                    </div>
                @elseif($item->status === 'menunggu')
                    <div class="text-[10px] text-yellow-600 font-black bg-yellow-50 px-3 py-2 rounded-xl animate-pulse">
                        Proses Verifikasi
                    </div>
                @else
                    <a href="{{ route('bayar', ['iuran_id' => $item->id]) }}"
                        class="flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-xl transition-all shadow-sm shadow-emerald-200 active:scale-95">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Bayar Sekarang
                    </a>
                @endif
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-gray-400">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="font-medium text-sm tracking-tight text-gray-300">Belum ada tagihan iuran</p>
        </div>
        @endforelse
    </div>

    <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/40 flex items-center justify-between">
        <p class="text-xs text-gray-400">Jatuh tempo setiap tanggal 28</p>
        {{ $iuran->links('vendor.pagination.simple-tailwind') }}
    </div>
</div>

@endsection
