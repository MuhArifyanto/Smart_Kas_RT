@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.warga')
@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-5 gap-3">
        <div>
            <h3 class="text-base font-semibold text-gray-800 uppercase tracking-wider">Semua Notifikasi</h3>
            <p class="text-xs text-gray-400 mt-0.5">Pengingat dan informasi pembayaran iuran</p>
        </div>
        @if($notifikasi->total() > 0)
        <form method="POST" action="{{ route('notifikasi.baca-semua') }}" class="w-auto sm:w-auto">
            @csrf
            <button type="submit"
                class="text-[11px] text-blue-600 hover:underline font-bold px-3 py-1.5 bg-blue-50 rounded-xl transition-all hover:bg-blue-100 flex items-center justify-start gap-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Tandai semua dibaca
            </button>
        </form>
        @endif
    </div>


    @if(session('status'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
            {{ session('status') }}
        </div>
    @endif

    {{-- List Notifikasi --}}
    @forelse($notifikasi as $item)
    <div class="bg-white rounded-2xl border mb-3 overflow-hidden transition-all
        {{ $item->sudahDibaca() ? 'border-gray-100 opacity-80' : 'border-blue-100 shadow-sm ring-1 ring-blue-50' }}
        {{ $item->tipe === 'peringatan' ? 'hover:border-orange-200 hover:shadow-md' : '' }}">
        
        @if($item->tipe === 'peringatan')
        <a href="{{ route('bayar') }}" class="block relative flex items-start gap-3 p-4 hover:bg-orange-50/30 transition-colors">
        @else
        <div class="relative flex items-start gap-3 p-4">
        @endif

            {{-- Icon tipe --}}
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                @if($item->tipe === 'sukses') bg-green-100
                @elseif($item->tipe === 'peringatan') bg-orange-100
                @else bg-blue-100 @endif font-bold">
                @if($item->tipe === 'sukses')
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @elseif($item->tipe === 'peringatan')
                    <svg class="w-5 h-5 text-orange-500 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                @else
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
            </div>

            {{-- Konten --}}
            <div class="flex-1 min-w-0 pr-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                    <p class="text-[14px] font-bold text-gray-800 leading-tight {{ $item->sudahDibaca() ? 'font-medium' : '' }}">
                        {{ $item->judul }}
                        @if(!$item->sudahDibaca())
                            <span class="inline-block w-2 h-2 bg-blue-500 rounded-full ml-1 align-middle"></span>
                        @endif
                    </p>
                    <span class="text-[10px] text-gray-400 font-medium sm:flex-shrink-0 uppercase tracking-tighter">{{ $item->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">{{ $item->pesan }}</p>
                
                @if($item->tipe === 'peringatan')
                <div class="mt-2 text-[11px] font-bold text-orange-600 flex items-center gap-1 uppercase tracking-tight">
                    <span>Klik untuk bayar iuran</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
                @endif
            </div>

        @if($item->tipe === 'peringatan')
        </a>
        @else
        </div>
        @endif

        {{-- Hapus (Tetap terpisah dari link agar tidak terpicu saat menghapus) --}}
        <div class="absolute right-4 top-4 z-10">
            <form method="POST" action="{{ route('notifikasi.hapus', $item) }}">
                @csrf @method('DELETE')
                <button type="submit" class="text-gray-300 hover:text-red-400 transition-colors p-1" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </form>
        </div>

        {{-- Garis bawah belum dibaca --}}
        @if(!$item->sudahDibaca())
        <div class="h-1 bg-gradient-to-r from-blue-400 via-blue-300 to-transparent"></div>
        @endif
    </div>

    @empty
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 ring-4 ring-gray-50/50">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </div>
        <p class="text-gray-600 font-bold text-lg">Tidak ada notifikasi</p>
        <p class="text-gray-400 text-sm mt-1">Semua notifikasi penting akan muncul di sini</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($notifikasi->hasPages())
    <div class="mt-6">{{ $notifikasi->links() }}</div>
    @endif


</div>
@endsection
