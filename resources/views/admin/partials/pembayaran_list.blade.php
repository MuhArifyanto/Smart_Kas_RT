@forelse($pembayaran as $p)
<div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 px-5 py-4 hover:bg-gray-50/50 transition-colors">
    <div class="flex items-center gap-3 w-full sm:w-auto">
        {{-- Avatar --}}
        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm flex-shrink-0">
            {{ strtoupper(substr($p->user->name ?? '?', 0, 1)) }}
        </div>

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <p class="font-semibold text-gray-800 text-sm truncate">{{ $p->user->name ?? '-' }}</p>
            <p class="text-[10px] text-gray-400 uppercase font-medium">
                {{ $p->labelMetode() }} &bull;
                {{ $p->dibayar_at ? $p->dibayar_at->diffForHumans() : $p->created_at->diffForHumans() }}
            </p>
        </div>

        {{-- Status (Mobile Only) --}}
        @if($p->status !== 'menunggu')
        <div class="sm:hidden">
            <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $p->colorStatus() }}">
                {{ $p->labelStatus() }}
            </span>
        </div>
        @endif
    </div>

    <div class="flex items-center justify-between w-full sm:w-auto sm:ml-auto gap-4">
        {{-- Bukti --}}
        @if($p->bukti_bayar)
        <a href="{{ '/storage/' . $p->bukti_bayar }}" target="_blank"
            class="text-[10px] font-bold uppercase text-blue-600 bg-blue-50 px-2 py-1 rounded-lg hover:bg-blue-100 transition-colors flex-shrink-0">
            Bukti
        </a>
        @endif

        {{-- Nominal --}}
        <p class="font-bold text-gray-800 text-sm flex-shrink-0">
            Rp {{ number_format($p->jumlah, 0, ',', '.') }}
        </p>

        {{-- Status / Aksi (Desktop Only) --}}
        <div class="hidden sm:block">
            @if($p->status === 'menunggu')
            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('admin.pembayaran.setujui', $p) }}">
                    @csrf
                    <button type="submit"
                        class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-colors">
                        Setujui
                    </button>
                </form>
                <button onclick="openTolak({{ $p->id }})"
                    class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition-colors">
                    Tolak
                </button>
            </div>
            @else
            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $p->colorStatus() }}">
                {{ $p->labelStatus() }}
            </span>
            @endif
        </div>
    </div>

    {{-- Aksi (Mobile Only) --}}
    @if($p->status === 'menunggu')
    <div class="grid grid-cols-2 gap-2 w-full sm:hidden pt-1">
        <form method="POST" action="{{ route('admin.pembayaran.setujui', $p) }}" class="flex-1">
            @csrf
            <button type="submit"
                class="w-full py-2 bg-green-600 text-white text-[10px] font-bold uppercase rounded-xl transition-colors active:scale-95">
                Setujui
            </button>
        </form>
        <button onclick="openTolak({{ $p->id }})"
            class="w-full py-2 bg-red-500 text-white text-[10px] font-bold uppercase rounded-xl transition-colors active:scale-95">
            Tolak
        </button>
    </div>
    @endif
</div>
@empty
<div class="px-5 py-12 text-center text-gray-400">
    <p class="font-medium">Belum ada data pembayaran</p>
</div>
@endforelse
