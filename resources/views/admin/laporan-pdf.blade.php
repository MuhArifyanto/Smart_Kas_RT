<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        @media print { .no-print { display: none !important; } body { background: white !important; } }
        .table-laporan th, .table-laporan td { border: 1px solid #e5e7eb; }
        .table-laporan tr:nth-child(even) { background-color: #f9fafb; }
    </style>
</head>
<body class="bg-gray-50 py-8 px-4 font-sans">

    {{-- Tombol aksi --}}
    <div class="no-print max-w-4xl mx-auto mb-6 flex justify-end gap-3">
        <button onclick="downloadPDF()"
            class="flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Download PDF
        </button>
        <button onclick="window.print()"
            class="flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-lg transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Laporan
        </button>
        <a href="{{ route('admin.laporan', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
            class="flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-lg hover:bg-slate-50 transition-all shadow-sm">
            ← Kembali
        </a>
    </div>

    <div id="laporanDoc" class="max-w-4xl mx-auto bg-white p-10 shadow-sm border border-slate-100">
        
        {{-- Header Copy --}}
        <div class="flex items-start justify-between border-b-2 border-slate-800 pb-6 mb-8">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo.png') }}" class="h-16 w-auto" alt="Logo">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Smart Kas RT</h1>
                    <p class="text-slate-500 text-sm font-medium">Sistem Manajemen Kas & Iuran Warga</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-lg font-bold text-slate-900">LAPORAN KEUANGAN</h2>
                <p class="text-slate-500 font-bold uppercase tracking-wider text-xs">Periode: {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</p>
            </div>
        </div>

        {{-- Ringkasan dalam bentuk tabel sederhana --}}
        <div class="mb-10">
            <h3 class="text-xs font-black uppercase text-slate-400 tracking-widest mb-3">Ringkasan Saldo</h3>
            <table class="w-full text-sm border-collapse">
                <tbody>
                    <tr class="border-b border-slate-100">
                        <td class="py-2 text-slate-600">Total Pemasukan Bulan Ini</td>
                        <td class="py-2 text-right font-bold text-slate-900">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2 text-slate-600">Total Pengeluaran Bulan Ini</td>
                        <td class="py-2 text-right font-bold text-slate-900">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="py-3 px-2 font-bold text-slate-900 text-base">Saldo Akhir</td>
                        <td class="py-3 px-2 text-right font-black text-slate-900 text-lg">
                            Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 gap-10">
            {{-- Pemasukan --}}
            <div>
                <div class="flex items-center justify-between mb-4 border-l-4 border-slate-800 pl-3">
                    <h3 class="text-sm font-black uppercase text-slate-900 tracking-wider">Rincian Pemasukan</h3>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Total: Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</span>
                </div>
                <table class="w-full text-xs text-left border-collapse table-laporan">
                    <thead>
                        <tr class="bg-slate-50 text-slate-700">
                            <th class="px-3 py-2 font-bold uppercase tracking-tighter">Tanggal</th>
                            <th class="px-3 py-2 font-bold uppercase tracking-tighter">Keterangan / Sumber</th>
                            <th class="px-3 py-2 font-bold uppercase tracking-tighter text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemasukan as $p)
                        <tr class="text-slate-600">
                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($p->dibayar_at)->format('d/m/Y') }}</td>
                            <td class="px-3 py-2 italic">Iuran - {{ $p->user->name ?? 'Warga' }}</td>
                            <td class="px-3 py-2 text-right font-bold text-slate-900">Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-4 py-8 text-center text-slate-400 italic">Data pemasukan tidak ditemukan pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pengeluaran --}}
            <div>
                <div class="flex items-center justify-between mb-4 border-l-4 border-slate-800 pl-3">
                    <h3 class="text-sm font-black uppercase text-slate-900 tracking-wider">Rincian Pengeluaran</h3>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Total: Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                </div>
                <table class="w-full text-xs text-left border-collapse table-laporan">
                    <thead>
                        <tr class="bg-slate-50 text-slate-700">
                            <th class="px-3 py-2 font-bold uppercase tracking-tighter">Tanggal</th>
                            <th class="px-3 py-2 font-bold uppercase tracking-tighter">Keterangan Pengeluaran</th>
                            <th class="px-3 py-2 font-bold uppercase tracking-tighter text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengeluaran as $p)
                        <tr class="text-slate-600">
                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                            <td class="px-3 py-2">{{ $p->keterangan }}</td>
                            <td class="px-3 py-2 text-right font-bold text-slate-900">Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-4 py-8 text-center text-slate-400 italic">Data pengeluaran tidak ditemukan pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TTD --}}
        <div class="mt-16 flex justify-between items-center px-10">
            <div class="text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase mb-16 tracking-widest">Mengetahui,<br>Ketua RT</p>
                <div class="w-40 border-b border-slate-900 mx-auto mb-1"></div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">NIP. ..........................</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase mb-16 tracking-widest">Bendahara RT,<br>Dibuat Pada {{ now()->format('d/m/Y') }}</p>
                <div class="w-40 border-b border-slate-900 mx-auto mb-1"></div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">NIP. ..........................</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-20 border-t border-slate-100 pt-4 flex justify-between text-[9px] font-bold text-slate-400 uppercase tracking-widest">
            <span>Smart Kas RT — Laporan Valid</span>
            <span>Halaman 1 dari 1</span>
        </div>
    </div>

    <script>
    function downloadPDF() {
        const el = document.getElementById('laporanDoc');
        const opt = {
            margin: [10, 10],
            filename: 'Laporan_Keuangan_{{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format("F_Y") }}.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, letterRendering: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(el).save();
    }

    // Auto print handling
    window.onload = () => {
        if (!window.location.search.includes('noprint')) {
            setTimeout(() => {
                // window.print(); // Uncomment this if you want auto-print dialog
            }, 1000);
        }
    };
    </script>
</body>
</html>
