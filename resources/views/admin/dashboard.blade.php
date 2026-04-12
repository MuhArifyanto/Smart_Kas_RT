@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- System Status Check --}}
@php
    $storageLinked = file_exists(public_path('storage'));
    $mailConfigured = config('mail.mailers.smtp.host') && config('mail.mailers.smtp.host') != 'mailpit';
@endphp

@if(!$storageLinked || !$mailConfigured)
<div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
    @if(!$storageLinked)
    <div class="flex items-center gap-4 p-4 bg-white border border-red-100 rounded-2xl shadow-sm">
        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-xs font-bold text-red-600 uppercase tracking-wider">Storage Link Terputus</p>
            <p class="text-[11px] text-gray-500 mt-0.5 leading-relaxed">Media & gambar tidak akan muncul. Jalankan <code class="bg-red-50 px-1 rounded text-red-700">php artisan storage:link</code>.</p>
        </div>
    </div>
    @endif

    @if(!$mailConfigured)
    <div class="flex items-center gap-4 p-4 bg-white border border-amber-100 rounded-2xl shadow-sm">
        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Email Belum Siap</p>
            <p class="text-[11px] text-gray-500 mt-0.5 leading-relaxed">Pengiriman kwitansi dinonaktifkan. Periksa konfigurasi SMTP di file <code class="bg-amber-50 px-1 rounded text-amber-700">.env</code>.</p>
        </div>
    </div>
    @endif
</div>
@endif

{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 mb-1">Total Saldo Kas</p>
        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($stats['total_saldo'], 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">Pemasukan - Pengeluaran</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 mb-1">Jumlah Warga</p>
        <p class="text-xl font-bold text-gray-800">{{ $stats['jumlah_warga'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Warga terdaftar</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 mb-1">Pemasukan Bulan Ini</p>
        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($stats['pemasukan_bulan'], 0, ',', '.') }}</p>
        @if($insights['selisihPemasukan'] != 0)
        <p class="text-xs mt-1 {{ $insights['selisihPemasukan'] > 0 ? 'text-green-500' : 'text-red-400' }}">
            {{ $insights['selisihPemasukan'] > 0 ? '↑' : '↓' }} {{ abs($insights['selisihPemasukan']) }}% dari bulan lalu
        </p>
        @else
        <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
        @endif
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between">
        <div>
            <p class="text-xs text-gray-500 mb-1">Pengeluaran Bulan Ini</p>
            <p class="text-xl font-bold text-gray-800">Rp {{ number_format($stats['pengeluaran_bulan'], 0, ',', '.') }}</p>
        </div>
        <div class="mt-2 pt-2 border-t border-gray-50 flex items-center justify-between">
            <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Savings Rate</span>
            <span class="text-xs font-bold {{ $insights['savingsRate'] > 20 ? 'text-emerald-500' : ($insights['savingsRate'] > 0 ? 'text-blue-500' : 'text-red-500') }}">
                {{ $insights['savingsRate'] }}%
            </span>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    {{-- Pie Chart: Status Pembayaran --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">
            Status Pembayaran — {{ now()->translatedFormat('F Y') }}
        </h3>
        @if($pieData['total'] > 0)
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="relative w-40 h-40 flex-shrink-0 mx-auto md:mx-0">
                <canvas id="pieChart"></canvas>
            </div>
            <div class="space-y-3 text-sm flex-1 w-full">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-green-500 flex-shrink-0"></span>
                    <span class="text-gray-600">Lunas</span>
                    <span class="ml-auto font-semibold text-gray-800">{{ $pieData['lunas'] }} ({{ $pieData['persen_lunas'] }}%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-yellow-400 flex-shrink-0"></span>
                    <span class="text-gray-600">Menunggu</span>
                    <span class="ml-auto font-semibold text-gray-800">{{ $pieData['menunggu'] }} ({{ $pieData['persen_menunggu'] }}%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-400 flex-shrink-0"></span>
                    <span class="text-gray-600">Belum Bayar</span>
                    <span class="ml-auto font-semibold text-gray-800">{{ $pieData['belum_bayar'] }} ({{ $pieData['persen_belum'] }}%)</span>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <p class="text-xs text-gray-400">Total: {{ $pieData['total'] }} tagihan</p>
                </div>
            </div>
        </div>
        @else
        <div class="flex flex-col items-center justify-center h-40 text-gray-400">
            <svg class="w-10 h-10 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm">Belum ada tagihan bulan ini</p>
        </div>
        @endif
    </div>

    {{-- Line Chart: Pemasukan per Bulan --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Pemasukan per Bulan</h3>
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-lg">{{ now()->year }}</span>
        </div>
        <canvas id="lineChart" height="140"></canvas>
    </div>

</div>

{{-- Analytics Section --}}
<div class="mb-6">
    {{-- Bar Chart: Pemasukan vs Pengeluaran --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Perbandingan Kas Bulanan</h3>
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-lg">6 Bulan Terakhir</span>
        </div>
        <canvas id="barChart" height="80"></canvas>
    </div>
</div>



{{-- Smart Insights --}}
<div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
    <h3 class="text-sm font-semibold text-gray-700 mb-4">Smart Insights</h3>
    <div class="space-y-3">

        {{-- Warga belum bayar --}}
        @if($insights['belumBayar'] > 0)
        <div class="flex items-start gap-3 p-4 bg-orange-50 rounded-xl">
            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800">
                    {{ $insights['persen_belum'] }}% warga belum membayar bulan ini
                </p>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $insights['belumBayar'] }} tagihan belum dibayar — pertimbangkan kirim pengingat
                </p>
            </div>
        </div>
        @else
        <div class="flex items-start gap-3 p-4 bg-green-50 rounded-xl">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800">Semua warga sudah membayar bulan ini!</p>
                <p class="text-xs text-gray-500 mt-0.5">Tidak ada tunggakan iuran bulan {{ now()->translatedFormat('F Y') }}</p>
            </div>
        </div>
        @endif

        {{-- Tren pemasukan --}}
        @if($insights['selisihPemasukan'] != 0)
        <div class="flex items-start gap-3 p-4 {{ $insights['selisihPemasukan'] > 0 ? 'bg-green-50' : 'bg-red-50' }} rounded-xl">
            <div class="w-8 h-8 {{ $insights['selisihPemasukan'] > 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 {{ $insights['selisihPemasukan'] > 0 ? 'text-green-600' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $insights['selisihPemasukan'] > 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800">
                    Pemasukan {{ $insights['selisihPemasukan'] > 0 ? 'meningkat' : 'menurun' }}
                    {{ abs($insights['selisihPemasukan']) }}% dibanding bulan lalu
                </p>
                <p class="text-xs text-gray-500 mt-0.5">
                    Bulan ini: Rp {{ number_format($insights['pemasukanBulan'], 0, ',', '.') }}
                </p>
            </div>
        </div>
        @endif

        {{-- Tren pengeluaran --}}
        @if($insights['selisihPengeluaran'] != 0)
        <div class="flex items-start gap-3 p-4 {{ $insights['selisihPengeluaran'] < 0 ? 'bg-blue-50' : 'bg-red-50' }} rounded-xl">
            <div class="w-8 h-8 {{ $insights['selisihPengeluaran'] < 0 ? 'bg-blue-100' : 'bg-red-100' }} rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 {{ $insights['selisihPengeluaran'] < 0 ? 'text-blue-600' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $insights['selisihPengeluaran'] < 0 ? 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' : 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' }}"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800">
                    Pengeluaran {{ $insights['selisihPengeluaran'] < 0 ? 'menurun' : 'meningkat' }}
                    {{ abs($insights['selisihPengeluaran']) }}% dibanding bulan lalu
                </p>
                <p class="text-xs text-gray-500 mt-0.5">
                    Bulan ini: Rp {{ number_format($insights['pengeluaranBulan'], 0, ',', '.') }}
                </p>
            </div>
        </div>
        @endif

        {{-- Saldo operasional --}}
        @if($insights['bulanOperasional'] > 0)
        <div class="flex items-start gap-3 p-4 bg-indigo-50 rounded-xl">
            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800">
                    Dana Cadangan: <span class="text-emerald-600">Sangat Aman</span>
                </p>
                <p class="text-xs text-gray-500 mt-0.5">
                    Cukup untuk menanggung biaya operasional selama <strong>{{ $insights['bulanOperasional'] }} bulan</strong> ke depan.
                </p>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
@if($pieData['total'] > 0)
new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Lunas', 'Menunggu', 'Belum Bayar'],
        datasets: [{
            data: [{{ $pieData['lunas'] }}, {{ $pieData['menunggu'] }}, {{ $pieData['belum_bayar'] }}],
            backgroundColor: ['#22c55e', '#facc15', '#f87171'],
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        cutout: '65%',
        plugins: { legend: { display: false } },
        responsive: true,
        maintainAspectRatio: true,
    }
});
@endif

new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Pemasukan',
            data: {!! json_encode($chartPemasukan) !!},
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#3b82f6',
            pointRadius: 4,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                max: 5000000,
                grid: { color: 'rgba(0,0,0,0.05)' },
                ticks: {
                    stepSize: 1000000,
                    callback: v => {
                        if (v === 0) return 'Rp 0';
                        return 'Rp ' + (v/1000000).toFixed(0) + ' jt';
                    },
                    font: { size: 11 },
                    color: '#374151'
                }
            },
            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#374151' } }
        }
    }
});

new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [
            {
                label: 'Pemasukan',
                data: {!! json_encode($chartPemasukan) !!},
                backgroundColor: '#10b981',
                borderRadius: 4,
            },
            {
                label: 'Pengeluaran',
                data: {!! json_encode($chartPengeluaran) !!},
                backgroundColor: '#ef4444',
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true, position: window.innerWidth < 640 ? 'bottom' : 'top', align: window.innerWidth < 640 ? 'center' : 'end',
                labels: { boxWidth: 10, font: { size: 9, weight: 'bold' }, color: '#9ca3af', padding: 15 }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.03)' },
                ticks: {
                    callback: v => 'Rp ' + (v/1000).toFixed(0) + 'k',
                    font: { size: 10 }, color: '#9ca3af'
                }
            },
            x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#9ca3af' } }
        }
    }
});


</script>
@endpush
