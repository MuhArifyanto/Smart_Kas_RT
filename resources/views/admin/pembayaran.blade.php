@extends('layouts.admin')
@section('title', 'Pembayaran')
@section('page-title', 'Pembayaran')

@section('content')

@if(session('success'))
<div class="mb-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Summary --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center justify-between sm:block">
        <p class="text-xs sm:text-sm text-gray-500 mb-0 sm:mb-1 uppercase font-semibold sm:normal-case">Menunggu Verifikasi</p>
        <p id="sumMenunggu" class="text-2xl sm:text-3xl font-bold text-yellow-500 leading-none">{{ $summary['menunggu'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center justify-between sm:block">
        <p class="text-xs sm:text-sm text-gray-500 mb-0 sm:mb-1 uppercase font-semibold sm:normal-case">Disetujui</p>
        <p id="sumDisetujui" class="text-2xl sm:text-3xl font-bold text-green-600 leading-none">{{ $summary['disetujui'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center justify-between sm:block">
        <p class="text-xs sm:text-sm text-gray-500 mb-0 sm:mb-1 uppercase font-semibold sm:normal-case">Ditolak</p>
        <p id="sumDitolak" class="text-2xl sm:text-3xl font-bold text-red-500 leading-none">{{ $summary['ditolak'] }}</p>
    </div>
</div>

{{-- Pilih Metode (info) + Filter --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
    <h3 class="font-semibold text-gray-800 mb-4 text-sm sm:text-base">Informasi Metode Pembayaran</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl bg-blue-50/50 transition-all hover:border-blue-200">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800">E-Wallet</p>
                <p class="text-[10px] text-gray-400 uppercase tracking-tighter">OVO, DANA, GoPay</p>
            </div>
        </div>
        <div class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl bg-purple-50/50 transition-all hover:border-purple-200">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800">QRIS</p>
                <p class="text-[10px] text-gray-400 uppercase tracking-tighter">Scan untuk bayar</p>
            </div>
        </div>
        <div class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl bg-green-50/50 transition-all hover:border-green-200">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800">Transfer Bank</p>
                <p class="text-[10px] text-gray-400 uppercase tracking-tighter">Transfer manual</p>
            </div>
        </div>
    </div>
</div>

{{-- Verifikasi Pembayaran --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">Verifikasi Pembayaran</h3>
        <form method="GET" action="{{ route('admin.pembayaran') }}" class="flex items-center gap-2">
            <select name="status" onchange="this.form.submit()"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </form>
    </div>

    <div id="pembayaranList" class="divide-y divide-gray-50">
        @include('admin.partials.pembayaran_list', ['pembayaran' => $pembayaran])
    </div>

    <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between bg-gray-50/40">
        <p class="text-xs text-gray-400">{{ $pembayaran->total() }} total pembayaran</p>
        {{ $pembayaran->links('vendor.pagination.simple-tailwind') }}
    </div>
</div>

{{-- Modal Tolak --}}
<div id="modalTolak" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeTolak()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm z-10 p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Alasan Penolakan</h3>
        <form id="formTolak" method="POST">
            @csrf
            <textarea name="catatan" rows="3" placeholder="Tuliskan alasan penolakan (opsional)..."
                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 resize-none mb-4"></textarea>
            <div class="flex gap-3">
                <button type="button" onclick="closeTolak()"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl transition-colors">
                    Tolak Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openTolak(id) {
    document.getElementById('formTolak').action = `/admin/pembayaran/${id}/tolak`;
    const m = document.getElementById('modalTolak');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function closeTolak() {
    const m = document.getElementById('modalTolak');
    m.classList.add('hidden'); m.classList.remove('flex');
}

// Real-time polling
async function pollPembayaran() {
    try {
        const status = new URLSearchParams(window.location.search).get('status') || '';
        const res = await fetch(`{{ route('admin.pembayaran.poll') }}?status=${status}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        
        // Update list
        document.getElementById('pembayaranList').innerHTML = data.html;
        
        // Update summary
        document.getElementById('sumMenunggu').innerText = data.summary.menunggu;
        document.getElementById('sumDisetujui').innerText = data.summary.disetujui;
        document.getElementById('sumDitolak').innerText = data.summary.ditolak;
    } catch (e) {}
}

setInterval(pollPembayaran, 10000);
</script>
@endpush
