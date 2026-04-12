@extends('layouts.admin')
@section('title', 'Pengeluaran')
@section('page-title', 'Pengeluaran')

@section('content')

@if(session('success'))
<div class="mb-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Banner Total Pengeluaran --}}
<div class="rounded-2xl p-6 mb-6 text-white" style="background: linear-gradient(135deg, #1e3a5f 0%, #0369a1 100%)">
    <p class="text-blue-200 text-sm mb-1">Total Pengeluaran</p>
    <p class="text-3xl font-bold">Rp {{ number_format($totalPengeluaran ?? 13000000, 0, ',', '.') }}</p>
    <p class="text-blue-300 text-xs mt-1">Semua waktu</p>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-5 py-5 border-b border-gray-100 gap-4">
        <div>
            <h3 class="font-bold text-gray-800">Daftar Pengeluaran</h3>
            <p class="text-[10px] text-gray-400 uppercase font-semibold mt-0.5 tracking-wider">Riwayat Kas Keluar</p>
        </div>
        <button onclick="openModal('modalTambah')"
            class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengeluaran
        </button>
    </div>

    {{-- Tampilan Desktop --}}
    <table class="w-full text-sm hidden md:table">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50/60">
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Keterangan</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nominal</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($pengeluaran ?? [] as $item)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-5 py-4 font-medium text-gray-800">{{ $item->keterangan }}</td>
                <td class="px-5 py-4 font-semibold text-gray-800">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                <td class="px-5 py-4 text-gray-500">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </div>
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick='openEdit(@json($item))'
                            class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('admin.pengeluaran.destroy', $item) }}"
                            onsubmit="return confirm('Hapus data ini?')">
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
                <td colspan="4" class="px-5 py-12 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                    <p class="font-medium">Belum ada data pengeluaran</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tampilan Mobile --}}
    <div class="md:hidden divide-y divide-gray-50">
        @forelse($pengeluaran ?? [] as $item)
        <div class="p-5 hover:bg-gray-50/50 transition-colors">
            <div class="flex justify-between items-start mb-2">
                <div class="flex-1 min-w-0 pr-4">
                    <p class="font-bold text-gray-800 text-sm mb-1 uppercase tracking-tight">{{ $item->keterangan }}</p>
                    <div class="flex items-center gap-1.5 text-[10px] text-gray-400 font-medium italic">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </div>
                </div>
                <div class="flex flex-col items-end gap-3 flex-shrink-0">
                    <p class="font-black text-gray-900 text-base">
                        <span class="text-[10px] font-bold text-red-500 mr-0.5">Rp</span>{{ number_format($item->nominal, 0, ',', '.') }}
                    </p>
                    <div class="flex items-center gap-1">
                        <button onclick='openEdit(@json($item))'
                            class="p-2.5 text-blue-600 bg-blue-50 rounded-xl active:scale-90 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('admin.pengeluaran.destroy', $item) }}"
                            onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2.5 text-red-500 bg-red-50 rounded-xl active:scale-90 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="px-5 py-12 text-center text-gray-400">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
            </svg>
            <p class="font-medium text-sm">Belum ada data pengeluaran</p>
        </div>
        @endforelse
    </div>

    @if(!empty($pengeluaran) && method_exists($pengeluaran, 'links'))
    <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/40">
        {{ $pengeluaran->links('vendor.pagination.simple-tailwind') }}
    </div>
    @endif
</div>

{{-- Modal Tambah --}}
<div id="modalTambah" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalTambah')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Tambah Pengeluaran</h3>
            <button onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.pengeluaran.store') }}" class="px-6 py-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan</label>
                <input type="text" name="keterangan" required placeholder="Contoh: Perbaikan jalan RT"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nominal</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm">Rp</span>
                    <input type="number" name="nominal" required min="0" placeholder="0"
                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" onclick="closeModal('modalTambah')"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Edit Pengeluaran</h3>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="formEdit" method="POST" class="px-6 py-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan</label>
                <input type="text" id="editKeterangan" name="keterangan" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nominal</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm">Rp</span>
                    <input type="number" id="editNominal" name="nominal" required
                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                <input type="date" id="editTanggal" name="tanggal" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" onclick="closeModal('modalEdit')"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">Perbarui</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
function closeModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }
function openEdit(data) {
    document.getElementById('formEdit').action = `/admin/pengeluaran/${data.id}`;
    document.getElementById('editKeterangan').value = data.keterangan;
    document.getElementById('editNominal').value = data.nominal;
    document.getElementById('editTanggal').value = data.tanggal;
    openModal('modalEdit');
}
</script>
@endpush
