@extends('layouts.admin')
@section('title', 'Data Warga')
@section('page-title', 'Data Warga')

@section('content')

{{-- Alert --}}
@if(session('success'))
<div class="mb-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-5">
    <form method="GET" action="{{ route('admin.warga') }}" class="relative w-full sm:w-72">
        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </span>
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama warga..."
            class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
    </form>
    <button onclick="openModal('modalTambah')"
        class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Warga
    </button>
</div>

{{-- Tabel & Card View --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    {{-- Desktop Table --}}
    <table class="w-full text-sm hidden sm:table">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50/60">
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Alamat</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nomor HP</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($warga as $w)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($w->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $w->name }}</p>
                            <p class="text-xs text-gray-400">{{ $w->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 text-gray-600">{{ $w->alamat ?? '-' }}</td>
                <td class="px-5 py-4 text-gray-600">{{ $w->no_hp ?? '-' }}</td>
                <td class="px-5 py-4">
                    @if($w->status === 'aktif')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full">
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Nonaktif
                        </span>
                    @endif
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center justify-end gap-2">
                        {{-- Edit --}}
                        <button onclick='openEdit(@json($w))'
                            class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        {{-- Hapus --}}
                        <form method="POST" action="{{ route('admin.warga.destroy', $w) }}"
                            onsubmit="return confirm('Hapus warga {{ $w->name }}?')">
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
                <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="font-medium">Tidak ada data warga</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Mobile Card View --}}
    <div class="sm:hidden divide-y divide-gray-50">
        @forelse($warga as $w)
        <div class="p-4 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-base flex-shrink-0">
                        {{ strtoupper(substr($w->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $w->name }}</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider">{{ $w->email }}</p>
                    </div>
                </div>
                @if($w->status === 'aktif')
                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 bg-green-50 text-green-700 text-[10px] font-bold uppercase rounded-full">
                        <span class="w-1 h-1 bg-green-500 rounded-full"></span> Aktif
                    </span>
                @else
                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 bg-gray-50 text-gray-500 text-[10px] font-bold uppercase rounded-full">
                        <span class="w-1 h-1 bg-gray-400 rounded-full"></span> Nonaktif
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Alamat</p>
                    <p class="text-sm text-gray-700 tracking-tight leading-snug">{{ $w->alamat ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Nomor HP</p>
                    <p class="text-sm text-gray-700 tracking-tight leading-snug">{{ $w->no_hp ?? '-' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <button onclick='openEdit(@json($w))'
                    class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-blue-50 text-blue-600 text-xs font-bold rounded-xl transition-colors active:bg-blue-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </button>
                <form method="POST" action="{{ route('admin.warga.destroy', $w) }}"
                    class="flex-1"
                    onsubmit="return confirm('Hapus warga {{ $w->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 bg-red-50 text-red-500 text-xs font-bold rounded-xl transition-colors active:bg-red-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-gray-400">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <p class="font-medium text-sm">Tidak ada data warga</p>
        </div>
        @endforelse
    </div>

    {{-- Footer: total + pagination --}}
    <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between bg-gray-50/40">
        <p class="text-xs text-gray-400">
            Menampilkan {{ $warga->firstItem() ?? 0 }}–{{ $warga->lastItem() ?? 0 }} dari {{ $warga->total() }} warga
        </p>
        {{ $warga->links('vendor.pagination.simple-tailwind') }}
    </div>
</div>

{{-- ===== MODAL TAMBAH ===== --}}
<div id="modalTambah" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalTambah')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Tambah Warga Baru</h3>
            <button onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.warga.store') }}" class="px-6 py-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Nama lengkap warga"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" required placeholder="email@contoh.com"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                <input type="text" name="alamat" required placeholder="Jl. Contoh No. 1"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nomor HP</label>
                <input type="text" name="no_hp" required placeholder="08xxxxxxxxxx"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-400 mt-1">Password default = nomor HP</p>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modalTambah')"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL EDIT ===== --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Edit Data Warga</h3>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="formEdit" method="POST" class="px-6 py-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" id="editName" name="name" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                <input type="email" id="editEmail" name="email" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                <input type="text" id="editAlamat" name="alamat" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nomor HP</label>
                <input type="text" id="editNoHp" name="no_hp" required
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select id="editStatus" name="status" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modalEdit')"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) {
    const m = document.getElementById(id);
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function closeModal(id) {
    const m = document.getElementById(id);
    m.classList.add('hidden');
    m.classList.remove('flex');
}
function openEdit(data) {
    document.getElementById('formEdit').action = `/admin/warga/${data.id}`;
    document.getElementById('editName').value   = data.name;
    document.getElementById('editEmail').value  = data.email;
    document.getElementById('editAlamat').value = data.alamat ?? '';
    document.getElementById('editNoHp').value   = data.no_hp ?? '';
    document.getElementById('editStatus').value = data.status ?? 'aktif';
    openModal('modalEdit');
}
</script>
@endpush
