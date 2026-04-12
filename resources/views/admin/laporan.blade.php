@extends('layouts.admin')
@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')

{{-- Header + Filter --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <h3 class="font-semibold text-gray-800">Laporan Keuangan</h3>
    <form method="GET" action="{{ route('admin.laporan') }}" class="flex items-center gap-2">
        <select name="bulan" onchange="this.form.submit()"
            class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            @foreach(range(1,12) as $m)
            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                {{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
            </option>
            @endforeach
        </select>
        <select name="tahun" onchange="this.form.submit()"
            class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            @foreach(range(now()->year, now()->year - 4, -1) as $y)
            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-green-50 border border-green-100 rounded-2xl p-5">
        <p class="text-xs text-green-600 font-medium mb-1">Total Pemasukan</p>
        <p class="text-2xl font-bold text-green-700">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
    </div>
    <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
        <p class="text-xs text-red-500 font-medium mb-1">Total Pengeluaran</p>
        <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
    </div>
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
        <p class="text-xs text-blue-600 font-medium mb-1">Saldo Akhir</p>
        <p class="text-2xl font-bold {{ $saldoAkhir >= 0 ? 'text-blue-700' : 'text-red-600' }}">
            Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
        </p>
    </div>
</div>

{{-- Tabel Pemasukan --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
    <div class="px-5 py-3.5 border-b border-gray-100">
        <h4 class="font-semibold text-gray-800 text-sm">Pemasukan</h4>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50/60">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Keterangan</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nominal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($pemasukan as $p)
            <tr class="hover:bg-gray-50/50">
                <td class="px-5 py-3 text-gray-500">{{ \Carbon\Carbon::parse($p->dibayar_at)->format('d M Y') }}</td>
                <td class="px-5 py-3 text-gray-700">Iuran {{ $p->user->name ?? '-' }}</td>
                <td class="px-5 py-3 text-right font-medium text-green-600">Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="px-5 py-6 text-center text-gray-400 text-xs">Tidak ada pemasukan bulan ini</td></tr>
            @endforelse
        </tbody>
        @if($pemasukan->count())
        <tfoot>
            <tr class="bg-green-50/60 border-t border-green-100">
                <td colspan="2" class="px-5 py-3 text-sm font-bold text-green-700">Total Pemasukan</td>
                <td class="px-5 py-3 text-right font-bold text-green-700">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

{{-- Tabel Pengeluaran --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-3.5 border-b border-gray-100">
        <h4 class="font-semibold text-gray-800 text-sm">Pengeluaran</h4>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50/60">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Keterangan</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nominal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($pengeluaran as $p)
            <tr class="hover:bg-gray-50/50">
                <td class="px-5 py-3 text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                <td class="px-5 py-3 text-gray-700">{{ $p->keterangan }}</td>
                <td class="px-5 py-3 text-right font-medium text-red-500">Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="px-5 py-6 text-center text-gray-400 text-xs">Tidak ada pengeluaran bulan ini</td></tr>
            @endforelse
        </tbody>
        @if($pengeluaran->count())
        <tfoot>
            <tr class="bg-red-50/60 border-t border-red-100">
                <td colspan="2" class="px-5 py-3 text-sm font-bold text-red-600">Total Pengeluaran</td>
                <td class="px-5 py-3 text-right font-bold text-red-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

{{-- Tombol Export --}}
<div class="grid grid-cols-2 gap-4">
    <a href="{{ route('admin.laporan.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank"
        class="flex items-center justify-center gap-2 py-3.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Export PDF
    </a>
    <a href="{{ route('admin.laporan.excel', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
        class="flex items-center justify-center gap-2 py-3.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Export Excel
    </a>
</div>

@endsection
