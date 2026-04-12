@forelse($riwayat as $r)
<div class="flex items-center gap-4 px-5 py-3.5 transition-colors duration-200">
    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
        @if($r->status === 'disetujui') bg-green-100 @elseif($r->status === 'menunggu') bg-yellow-100 @else bg-red-100 @endif">
        <svg class="w-4 h-4 @if($r->status === 'disetujui') text-green-600 @elseif($r->status === 'menunggu') text-yellow-600 @else text-red-500 @endif"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-gray-800 tracking-tight">
            {{ $r->iuran ? \Carbon\Carbon::parse($r->iuran->bulan.'-01')->translatedFormat('F Y') : '-' }}
        </p>
        <p class="text-[11px] text-gray-400 font-medium">{{ $r->labelMetode() }} &bull; {{ $r->created_at->diffForHumans() }}</p>
    </div>
    <div class="text-right flex-shrink-0">
        <p class="text-sm font-bold text-gray-900 tracking-tighter">Rp {{ number_format($r->jumlah, 0, ',', '.') }}</p>
        <span class="text-[10px] font-bold uppercase {{ $r->status === 'disetujui' ? 'text-green-600' : ($r->status === 'menunggu' ? 'text-yellow-600' : 'text-red-500') }}">
            {{ $r->labelStatus() }}
        </span>
    </div>
</div>
@empty
<div class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada riwayat pembayaran</div>
@endforelse
