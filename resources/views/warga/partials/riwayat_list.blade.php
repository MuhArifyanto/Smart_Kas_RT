{{-- Desktop View --}}
<div class="hidden md:block overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50/60">
                <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-widest">Bulan</th>
                <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-widest">Metode</th>
                <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-widest">Jumlah</th>
                <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-widest">Tanggal</th>
                <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-widest">Status</th>
                <th class="text-right px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($riwayat as $r)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-5 py-4 font-bold text-gray-800">
                    {{ $r->iuran ? \Carbon\Carbon::parse($r->iuran->bulan.'-01')->translatedFormat('F Y') : '-' }}
                </td>
                <td class="px-5 py-4 text-gray-600 font-medium">
                    <span class="px-2 py-1 bg-gray-100 rounded-md text-[10px] uppercase">{{ $r->labelMetode() }}</span>
                </td>
                <td class="px-5 py-4 font-black text-gray-900 text-base">Rp {{ number_format($r->jumlah, 0, ',', '.') }}</td>
                <td class="px-5 py-4 text-gray-500 text-xs font-medium">{{ $r->created_at->format('d M Y, H:i') }}</td>
                <td class="px-5 py-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold rounded-full uppercase tracking-tighter {{ $r->colorStatus() }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                        {{ $r->labelStatus() }}
                    </span>
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        @if($r->status === 'disetujui')
                        <a href="{{ route('kwitansi', $r) }}" target="_blank"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold rounded-xl shadow-sm transition-all transform hover:scale-105">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Kwitansi
                        </a>
                        @endif
                        @if($r->bukti_bayar)
                        <a href="{{ '/storage/'.$r->bukti_bayar }}" target="_blank"
                            class="text-xs text-emerald-600 hover:underline font-bold bg-emerald-50 px-2 py-1 rounded-md">Bukti</a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-16 text-center">
                <div class="flex flex-col items-center gap-2 opacity-40">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="font-bold">Belum ada riwayat pembayaran</p>
                </div>
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Mobile View --}}
<div class="md:hidden space-y-4">
    @forelse($riwayat as $r)
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden transition-all active:scale-[0.98]">
        <div class="p-5">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Periode Iuran</h4>
                    <p class="text-base font-black text-gray-800">
                        {{ $r->iuran ? \Carbon\Carbon::parse($r->iuran->bulan.'-01')->translatedFormat('F Y') : '-' }}
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold rounded-full uppercase tracking-tighter {{ $r->colorStatus() }}">
                    {{ $r->labelStatus() }}
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-5 p-3 bg-gray-50 rounded-2xl">
                <div>
                    <h5 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Metode</h5>
                    <p class="text-sm font-bold text-gray-700 capitalize">{{ $r->labelMetode() }}</p>
                </div>
                <div>
                    <h5 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Jumlah</h5>
                    <p class="text-sm font-black text-emerald-600">Rp {{ number_format($r->jumlah, 0, ',', '.') }}</p>
                </div>
                <div class="col-span-2 pt-2 border-t border-gray-200/50">
                    <h5 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Waktu Transaksi</h5>
                    <p class="text-[11px] font-medium text-gray-500">{{ $r->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if($r->status === 'disetujui')
                <a href="{{ route('kwitansi', $r) }}" target="_blank"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-xl shadow-md active:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download Kwitansi
                </a>
                @endif
                @if($r->bukti_bayar)
                <a href="{{ asset('storage/'.$r->bukti_bayar) }}" target="_blank"
                    class="px-4 py-2.5 border border-emerald-100 text-emerald-600 text-xs font-bold rounded-xl hover:bg-emerald-50 transition-colors">
                    Bukti
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-sm">
        <p class="text-gray-400 font-bold">Belum ada riwayat pembayaran</p>
    </div>
    @endforelse
</div>

