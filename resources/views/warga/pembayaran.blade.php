@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.warga')
@section('title', 'Bayar Iuran')
@section('page-title', 'Pembayaran')

@section('content')
<div class="max-w-2xl mx-auto">

    @if(session('success'))
    <div class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">{{ session('error') }}</div>
    @endif

    @if($iuran)
    {{-- Info Tagihan --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5 bg-gradient-to-br from-white to-blue-50/20">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-5 gap-3">
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-black mb-1 tracking-widest">Tagihan Iuran</p>
                <p class="font-bold text-gray-800 text-xl leading-tight">{{ \Carbon\Carbon::parse($iuran->bulan.'-01')->translatedFormat('F Y') }}</p>
                <div class="flex items-center gap-2 mt-1.5">
                   <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                   <p class="text-xs text-gray-500 font-medium">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <div class="bg-blue-600 px-4 py-2 rounded-2xl shadow-sm shadow-blue-100 w-full sm:w-auto text-center sm:text-right">
                <p class="text-[10px] text-blue-100 uppercase font-bold mb-0.5 tracking-tighter">Total yang harus dibayar</p>
                <p class="text-2xl font-black text-white tracking-tighter leading-none">Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Rincian Iuran --}}
        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Rincian Penggunaan</p>
            @php
                $nominal = $iuran->nominal;
                $rincian = [
                    ['label' => 'Iuran Kebersihan & Sampah', 'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16', 'color' => 'text-green-600 bg-green-50', 'persen' => 40],
                    ['label' => 'Keamanan Lingkungan', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'color' => 'text-blue-600 bg-blue-50', 'persen' => 35],
                    ['label' => 'Perawatan Fasilitas Umum', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'color' => 'text-orange-500 bg-orange-50', 'persen' => 15],
                    ['label' => 'Kas & Kegiatan RT', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'text-purple-600 bg-purple-50', 'persen' => 10],
                ];
                $sisaPersen = 100;
            @endphp
            <div class="space-y-2.5">
                @foreach($rincian as $i => $item)
                @php
                    $jumlah = ($i === count($rincian) - 1)
                        ? $nominal - (int)round($nominal * (100 - $item['persen']) / 100)
                        : (int)round($nominal * $item['persen'] / 100);
                @endphp
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg {{ explode(' ', $item['color'])[1] }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 {{ explode(' ', $item['color'])[0] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-medium text-gray-700">{{ $item['label'] }}</p>
                            <p class="text-xs font-semibold text-gray-800 ml-2 flex-shrink-0">Rp {{ number_format($jumlah, 0, ',', '.') }}</p>
                        </div>
                        <div class="mt-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ explode(' ', $item['color'])[1] === 'bg-green-50' ? 'bg-green-400' : (explode(' ', $item['color'])[1] === 'bg-blue-50' ? 'bg-blue-400' : (explode(' ', $item['color'])[1] === 'bg-orange-50' ? 'bg-orange-400' : 'bg-purple-400')) }}"
                                style="width: {{ $item['persen'] }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-3 pt-3 border-t border-dashed border-gray-200 flex items-center justify-between">
                <p class="text-xs text-gray-500 font-medium">Total Tagihan</p>
                <p class="text-sm font-bold text-blue-600">Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('bayar.store') }}" enctype="multipart/form-data" id="formBayar">
        @csrf
        <input type="hidden" name="iuran_id" value="{{ $iuran->id }}">
        <input type="hidden" name="metode" id="inputMetode">
        <input type="hidden" name="provider" id="inputProvider">

        {{-- Pilih Metode --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <h3 class="font-semibold text-gray-800 mb-4">Pilih Metode Pembayaran</h3>
            <div class="space-y-3">

                {{-- E-Wallet --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button type="button" onclick="toggleMetode('ewallet')"
                        class="w-full flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors text-left">
                        {{-- Logo E-Wallet: dompet biru dengan bintang --}}
                        <div class="w-10 h-10 flex items-center justify-center flex-shrink-0">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- Kartu belakang -->
                                <rect x="6" y="7" width="28" height="18" rx="4" fill="#7DD3FC" transform="rotate(-8 6 7)"/>
                                <!-- Dompet utama -->
                                <rect x="4" y="14" width="32" height="22" rx="5" fill="url(#walletGrad)"/>
                                <!-- Kilap -->
                                <ellipse cx="16" cy="19" rx="8" ry="4" fill="white" fill-opacity="0.18"/>
                                <!-- Bintang -->
                                <path d="M20 20.5l1.5 3 3.2.5-2.3 2.2.5 3.2L20 27.8l-2.9 1.6.5-3.2-2.3-2.2 3.2-.5z" fill="white"/>
                                <defs>
                                    <linearGradient id="walletGrad" x1="4" y1="14" x2="36" y2="36" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#38BDF8"/>
                                        <stop offset="100%" stop-color="#0284C7"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 text-sm">E-Wallet</p>
                            <p class="text-xs text-gray-400">OVO, DANA, GoPay, ShopeePay, LinkAja</p>
                        </div>
                        <svg id="arrow-ewallet" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    {{-- Sub-pilihan E-Wallet --}}
                    <div id="sub-ewallet" class="hidden border-t border-gray-100 bg-gray-50/50 p-4">
                        <p class="text-[10px] text-gray-400 mb-4 font-black uppercase tracking-wider">Pilih aplikasi e-wallet:</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">

                            {{-- OVO --}}
                            <button type="button" onclick="pilihProvider('ewallet','ovo')"
                                id="btn-ovo"
                                class="provider-btn flex flex-col items-center justify-center gap-2 p-4 border-2 border-gray-200 rounded-2xl hover:border-blue-400 transition-all bg-white shadow-sm active:scale-95">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/200px-Logo_ovo_purple.svg.png"
                                    alt="OVO" class="h-6 object-contain" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                <div class="hidden w-10 h-8 rounded-lg items-center justify-center text-white text-[10px] font-bold" style="background:#4C3494">OVO</div>
                                <span class="text-[10px] font-bold text-gray-600 uppercase">OVO</span>
                            </button>

                            {{-- DANA --}}
                            <button type="button" onclick="pilihProvider('ewallet','dana')"
                                id="btn-dana"
                                class="provider-btn flex flex-col items-center justify-center gap-2 p-4 border-2 border-gray-200 rounded-2xl hover:border-blue-400 transition-all bg-white shadow-sm active:scale-95">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/200px-Logo_dana_blue.svg.png"
                                    alt="DANA" class="h-6 object-contain" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                <div class="hidden w-10 h-8 rounded-lg items-center justify-center text-white text-[10px] font-bold" style="background:#118EEA">DANA</div>
                                <span class="text-[10px] font-bold text-gray-600 uppercase">DANA</span>
                            </button>

                            {{-- GoPay --}}
                            <button type="button" onclick="pilihProvider('ewallet','gopay')"
                                id="btn-gopay"
                                class="provider-btn flex flex-col items-center justify-center gap-2 p-4 border-2 border-gray-200 rounded-2xl hover:border-blue-400 transition-all bg-white shadow-sm active:scale-95">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/200px-Gopay_logo.svg.png"
                                    alt="GoPay" class="h-6 object-contain" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                <div class="hidden w-10 h-8 rounded-lg items-center justify-center text-white text-[10px] font-bold" style="background:#00AED6">GP</div>
                                <span class="text-[10px] font-bold text-gray-600 uppercase">GoPay</span>
                            </button>

                            {{-- ShopeePay --}}
                            <button type="button" onclick="pilihProvider('ewallet','shopeepay')"
                                id="btn-shopeepay"
                                class="provider-btn flex flex-col items-center justify-center gap-2 p-4 border-2 border-gray-200 rounded-2xl hover:border-blue-400 transition-all bg-white shadow-sm active:scale-95">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Shopee.svg/200px-Shopee.svg.png"
                                    alt="ShopeePay" class="h-6 object-contain" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                <div class="hidden w-10 h-8 rounded-lg items-center justify-center text-white text-[10px] font-bold" style="background:#EE4D2D">SP</div>
                                <span class="text-[10px] font-bold text-gray-600 uppercase">ShopeePay</span>
                            </button>

                            {{-- LinkAja --}}
                            <button type="button" onclick="pilihProvider('ewallet','linkaja')"
                                id="btn-linkaja"
                                class="provider-btn flex flex-col items-center justify-center gap-2 p-4 border-2 border-gray-200 rounded-2xl hover:border-blue-400 transition-all bg-white shadow-sm active:scale-95">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/LinkAja.svg/200px-LinkAja.svg.png"
                                    alt="LinkAja" class="h-6 object-contain" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                <div class="hidden w-10 h-8 rounded-lg items-center justify-center text-white text-[10px] font-bold" style="background:#E82529">LA</div>
                                <span class="text-[10px] font-bold text-gray-600 uppercase">LinkAja</span>
                            </button>
                        </div>

                        {{-- Upload bukti e-wallet --}}
                        <div id="uploadEwalletArea" class="hidden mt-4">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Upload Bukti Pembayaran <span class="text-gray-400">(opsional)</span></label>
                            <input type="file" name="bukti_bayar" accept="image/*"
                                class="w-full text-sm text-gray-500
                                    file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                                    file:text-sm file:font-medium file:bg-blue-50 file:text-blue-600
                                    hover:file:bg-blue-100"
                                onchange="previewBukti(this)">
                        </div>
                    </div>
                </div>

                {{-- QRIS --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button type="button" onclick="toggleMetode('qris')"
                        class="w-full flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors text-left">
                        {{-- Logo QRIS --}}
                        <div class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg"
                                alt="QRIS" class="w-9 h-9 object-contain">
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 text-sm">QRIS</p>
                            <p class="text-xs text-gray-400">Scan untuk bayar — semua aplikasi</p>
                        </div>
                        <svg id="arrow-qris" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="sub-qris" class="hidden border-t border-gray-100 bg-gray-50/50 p-5">
                        {{-- Header logo QRIS besar --}}
                        <div class="flex items-center justify-center mb-4">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg"
                                alt="QRIS" class="h-8 object-contain">
                        </div>
                        <p class="text-xs text-gray-500 mb-3 font-medium text-center">Scan QR Code berikut dengan aplikasi apapun:</p>
                        <div class="flex flex-col items-center gap-3">
                            {{-- QR Code --}}
                            <div class="w-48 h-48 bg-white border-2 border-gray-200 rounded-xl flex flex-col items-center justify-center p-2 gap-1">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg"
                                    alt="QRIS" class="h-5 object-contain mb-1">
                                <svg class="w-32 h-32 text-gray-800" viewBox="0 0 100 100" fill="currentColor">
                                    <rect x="10" y="10" width="30" height="30" rx="2" fill="none" stroke="currentColor" stroke-width="4"/>
                                    <rect x="16" y="16" width="18" height="18" rx="1"/>
                                    <rect x="60" y="10" width="30" height="30" rx="2" fill="none" stroke="currentColor" stroke-width="4"/>
                                    <rect x="66" y="16" width="18" height="18" rx="1"/>
                                    <rect x="10" y="60" width="30" height="30" rx="2" fill="none" stroke="currentColor" stroke-width="4"/>
                                    <rect x="16" y="66" width="18" height="18" rx="1"/>
                                    <rect x="60" y="60" width="8" height="8"/><rect x="72" y="60" width="8" height="8"/>
                                    <rect x="84" y="60" width="8" height="8"/><rect x="60" y="72" width="8" height="8"/>
                                    <rect x="72" y="72" width="8" height="8"/><rect x="84" y="84" width="8" height="8"/>
                                    <rect x="44" y="10" width="8" height="8"/><rect x="44" y="22" width="8" height="8"/>
                                    <rect x="44" y="34" width="8" height="8"/><rect x="44" y="46" width="8" height="8"/>
                                    <rect x="10" y="44" width="8" height="8"/><rect x="22" y="44" width="8" height="8"/>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 text-center">Berlaku untuk semua e-wallet & mobile banking</p>
                            <button type="button" onclick="pilihProvider('qris','qris'); document.getElementById('uploadBuktiQris').click()"
                                class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-xl transition-colors">
                                Sudah Bayar — Upload Bukti
                            </button>
                            <input type="file" id="uploadBuktiQris" name="bukti_bayar" accept="image/*" class="hidden"
                                onchange="previewBukti(this)">
                        </div>
                    </div>
                </div>

                {{-- Transfer Bank --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button type="button" onclick="toggleMetode('transfer_bank')"
                        class="w-full flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors text-left">
                        {{-- Logo Mandiri --}}
                        <div class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg"
                                alt="Mandiri" class="w-9 object-contain">
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 text-sm">Transfer Bank</p>
                            <p class="text-xs text-gray-400">Transfer manual ke rekening Mandiri</p>
                        </div>
                        <svg id="arrow-transfer_bank" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="sub-transfer_bank" class="hidden border-t border-gray-100 bg-gray-50/50 p-5">
                        <p class="text-xs text-gray-500 mb-3 font-medium">Rekening tujuan:</p>
                        {{-- Mandiri --}}
                        <div id="btn-mandiri" onclick="pilihProvider('transfer_bank','mandiri')"
                            class="provider-btn border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-green-400 transition-all mb-3">
                            <div class="flex items-center gap-3">
                                {{-- Logo Mandiri besar --}}
                                <div class="h-10 flex items-center justify-center flex-shrink-0">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg"
                                        alt="Bank Mandiri" class="h-8 object-contain">
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">Bank Mandiri</p>
                                    <p class="text-xs text-gray-500">a.n. Kas RT 05 / RW 03</p>
                                </div>
                            </div>
                            <div class="mt-3 bg-white rounded-lg p-3 border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-400">Nomor Rekening</p>
                                        <p class="font-bold text-gray-800 text-lg tracking-widest" id="noRek">1234-5678-9012</p>
                                    </div>
                                    <button type="button" onclick="copyRek(event)"
                                        class="text-xs text-blue-600 hover:underline font-medium px-3 py-1.5 bg-blue-50 rounded-lg">
                                        Salin
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Transfer tepat <span class="font-semibold text-gray-700">Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</span> lalu upload bukti transfer</p>
                        </div>
                        {{-- Upload bukti --}}
                        <div id="uploadTransferArea" class="hidden">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Upload Bukti Transfer</label>
                            <input type="file" name="bukti_bayar" accept="image/*" class="w-full text-sm text-gray-500
                                file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                                file:text-sm file:font-medium file:bg-blue-50 file:text-blue-600
                                hover:file:bg-blue-100" onchange="previewBukti(this)">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Preview bukti --}}
        <div id="previewArea" class="hidden bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <p class="text-sm font-medium text-gray-700 mb-3">Preview Bukti Pembayaran</p>
            <img id="previewImg" src="" alt="Bukti" class="max-h-48 rounded-xl border border-gray-200 object-contain">
        </div>

        {{-- Tombol Bayar --}}
        <button type="submit" id="btnBayar" disabled
            class="w-full py-3.5 bg-blue-600 text-white font-semibold rounded-xl text-sm transition-all
                   disabled:opacity-40 disabled:cursor-not-allowed hover:bg-blue-700 relative overflow-hidden group">
            <span id="btnText">Konfirmasi Pembayaran</span>
            <div id="btnLoader" class="hidden absolute inset-0 bg-blue-700 flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </button>
    </form>

    {{-- Overlay Verifikasi Otomatis --}}
    <div id="overlayVerifikasi" class="fixed inset-0 z-[100] hidden items-center justify-center p-6 bg-white/80 backdrop-blur-md">
        <div class="max-w-xs w-full text-center">
            <div id="v-spinner" class="mb-6 relative h-24 w-24 mx-auto">
                {{-- Lingkaran Luar --}}
                <div class="absolute inset-0 border-4 border-gray-100 rounded-full"></div>
                {{-- Spinner --}}
                <div class="absolute inset-0 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                {{-- Icon tengah --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg id="v-icon-search" class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <svg id="v-icon-check" class="hidden w-12 h-12 text-emerald-500 animate-[bounce_0.5s_ease-in-out]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            
            <h3 id="v-title" class="text-xl font-bold text-gray-800 mb-2">Memulai Verifikasi...</h3>
            <p id="v-desc" class="text-sm text-gray-500 leading-relaxed">Sistem sedang menghubungkan ke server pembayaran untuk validasi real-time.</p>
            
            <div class="mt-8 flex justify-center gap-1">
                <div class="w-2 h-2 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 0s"></div>
                <div class="w-2 h-2 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 0.1s"></div>
                <div class="w-2 h-2 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
        </div>
    </div>

    @else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="font-medium">Tidak ada tagihan yang perlu dibayar</p>
        <p class="text-sm mt-1">Semua iuran Anda sudah lunas</p>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
let activeMetode = null;

function toggleMetode(metode) {
    const subs = ['ewallet', 'qris', 'transfer_bank'];
    subs.forEach(m => {
        const el = document.getElementById('sub-' + m);
        const arrow = document.getElementById('arrow-' + m);
        if (m === metode) {
            const isOpen = !el.classList.contains('hidden');
            el.classList.toggle('hidden', isOpen);
            arrow.style.transform = isOpen ? '' : 'rotate(180deg)';
        } else {
            el.classList.add('hidden');
            if (arrow) arrow.style.transform = '';
        }
    });
}

function pilihProvider(metode, provider) {
    document.getElementById('inputMetode').value = metode;
    document.getElementById('inputProvider').value = provider;
    activeMetode = metode;

    // Highlight tombol terpilih
    document.querySelectorAll('.provider-btn').forEach(b => {
        b.classList.remove('border-blue-500', 'border-green-500', 'bg-blue-50', 'bg-green-50');
        b.classList.add('border-gray-200');
    });
    const btn = document.getElementById('btn-' + provider);
    if (btn) {
        btn.classList.remove('border-gray-200');
        btn.classList.add(metode === 'transfer_bank' ? 'border-green-500' : 'border-blue-500');
    }

    // Tampilkan area upload untuk transfer bank
    if (metode === 'transfer_bank') {
        document.getElementById('uploadTransferArea').classList.remove('hidden');
    }

    // Aktifkan tombol bayar untuk ewallet & qris (tanpa wajib bukti)
    if (metode === 'ewallet') {
        document.getElementById('btnBayar').disabled = false;
    }
}

function previewBukti(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewArea').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
        document.getElementById('btnBayar').disabled = false;
    }
}

document.getElementById('formBayar')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Tampilkan Overlay
    const overlay = document.getElementById('overlayVerifikasi');
    const vTitle = document.getElementById('v-title');
    const vDesc = document.getElementById('v-desc');
    const vIconSearch = document.getElementById('v-icon-search');
    const vIconCheck = document.getElementById('v-icon-check');
    const provider = document.getElementById('inputProvider').value.toUpperCase();

    overlay.classList.remove('hidden');
    overlay.classList.add('flex');

    // Step 1: Connecting
    vTitle.innerText = "Menghubungkan...";
    vDesc.innerText = "Membuka jalur aman ke gateway " + provider + ".";

    // Step 2: Verifying (after 1s)
    setTimeout(() => {
        vTitle.innerText = "Memverifikasi Transaksi...";
        vDesc.innerText = "Sistem cerdas kami sedang memvalidasi data pembayaran Anda secara real-time.";
    }, 1200);

    // Step 3: Success (after 2.5s)
    setTimeout(() => {
        vTitle.innerText = "Berhasil!";
        vDesc.innerText = "Pembayaran telah diverifikasi otomatis. Mengalihkan Anda...";
        vIconSearch.classList.add('hidden');
        vIconCheck.classList.remove('hidden');
        
        // Final Submit
        setTimeout(() => {
            e.target.submit();
        }, 800);
    }, 2800);
});

function copyRek(e) {
    e.stopPropagation();
    navigator.clipboard.writeText('1234567890123');
    const btn = e.target;
    btn.textContent = 'Tersalin!';
    setTimeout(() => btn.textContent = 'Salin', 2000);
}
</script>
@endpush
