@extends('layouts.warga')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Greeting --}}
<div class="mb-6 p-5 rounded-2xl text-white" style="background: linear-gradient(135deg, #059669, #047857)">
    <p class="text-emerald-200 text-sm">Selamat datang kembali,</p>
    <h2 class="text-2xl font-bold mt-0.5">{{ auth()->user()->name }} 👋</h2>
    <p class="text-emerald-200 text-sm mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
</div>

{{-- Status Iuran & Grafik Kepatuhan --}}
<div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
    {{-- Left Stats (Expanded to full width) --}}
    <div class="md:col-span-12 grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Iuran Bulan Ini</p>
            @if($iuranBulanIni)
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($iuranBulanIni->nominal, 0, ',', '.') }}</p>
                <div class="mt-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full
                        @if($iuranBulanIni->status === 'lunas') bg-green-100 text-green-700
                        @elseif($iuranBulanIni->status === 'menunggu') bg-yellow-100 text-yellow-700
                        @else bg-red-100 text-red-600 @endif">
                        <span class="w-2 h-2 rounded-full
                            @if($iuranBulanIni->status === 'lunas') bg-green-500
                            @elseif($iuranBulanIni->status === 'menunggu') bg-yellow-500
                            @else bg-red-500 @endif"></span>
                        {{ $iuranBulanIni->labelStatus() }}
                    </span>
                </div>
            @else
                <p class="text-sm text-gray-400 mt-1">Belum ada tagihan</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Total Lunas</p>
            <div class="flex items-baseline gap-2">
                <p id="totalLunas" class="text-4xl font-black text-emerald-600">{{ $totalLunas }}</p>
                <p class="text-xs font-medium text-gray-400">bulan terbayar</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Belum Dibayar</p>
            <div class="flex items-baseline gap-2">
                <p id="totalBelum" class="text-4xl font-black text-rose-500">{{ $totalBelum }}</p>
                <p class="text-xs font-medium text-gray-400">tagihan tertunggak</p>
            </div>
        </div>
    </div>
</div>

{{-- CTA Bayar --}}
@if($iuranBulanIni && $iuranBulanIni->status !== 'lunas')
<div class="mb-6 p-4 bg-orange-50 border border-orange-200 rounded-2xl flex items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">Iuran bulan ini belum dibayar</p>
            <p class="text-xs text-gray-500">Rp {{ number_format($iuranBulanIni->nominal, 0, ',', '.') }} — {{ \Carbon\Carbon::parse($iuranBulanIni->bulan.'-01')->translatedFormat('F Y') }}</p>
        </div>
    </div>
    <a href="{{ route('bayar', ['iuran_id' => $iuranBulanIni->id]) }}"
        class="flex-shrink-0 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-colors">
        Bayar Sekarang
    </a>
</div>
@endif

{{-- Riwayat Terbaru --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 text-sm">Riwayat Pembayaran Terbaru</h3>
        <a href="{{ route('warga.riwayat') }}" class="text-xs text-emerald-600 hover:underline">Lihat semua</a>
    </div>
    <div id="recentRiwayatList" class="divide-y divide-gray-50">
        @include('warga.partials.recent_riwayat_list', ['riwayat' => $riwayat])
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time polling
    async function pollDashboard() {
        try {
            const res = await fetch('{{ route("warga.riwayat.poll") }}', {
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!res.ok) throw new Error('Network response was not ok');
            
            const data = await res.json();
            
            // Update stats
            if (document.getElementById('totalLunas')) {
                document.getElementById('totalLunas').innerText = data.totalLunas;
            }
            if (document.getElementById('totalBelum')) {
                document.getElementById('totalBelum').innerText = data.totalBelum;
            }
            
            // Update list
            if (document.getElementById('recentRiwayatList')) {
                document.getElementById('recentRiwayatList').innerHTML = data.html_dashboard;
            }
            
        } catch (e) {
            console.error('Dashboard polling error:', e);
        }
    }
    
    // Start polling every 10 seconds
    setInterval(pollDashboard, 10000);
});
</script>
@endpush
@endsection
