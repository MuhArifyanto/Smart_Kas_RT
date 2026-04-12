<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi #KWT-{{ str_pad($pembayaran->id, 6, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; margin: 0; padding: 0; }
            .kwitansi-card { box-shadow: none !important; border: 1px solid #000; margin: 0 !important; max-width: 100% !important; }
        }
        .kwitansi-card { border: 1px solid #e5e7eb; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-8 px-4">

    {{-- Tombol aksi --}}
    <div class="no-print max-w-2xl mx-auto mb-4 flex items-center justify-between">
        <a href="{{ route('warga.riwayat') }}"
            class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
        <div class="flex items-center gap-2">
            <button onclick="downloadPDF()"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download PDF
            </button>
            <button onclick="window.print()"
                class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white text-sm font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print
            </button>
        </div>
    </div>

    {{-- Kwitansi --}}
    <div id="kwitansiDoc" class="kwitansi-card max-w-2xl mx-auto bg-white overflow-hidden" style="border: 1px solid #000;">

        {{-- Header --}}
        <div class="px-8 py-8 border-b-2 border-black">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-black">SMART KAS RT</h1>
                    <p class="text-gray-600 text-xs mt-1 uppercase tracking-widest font-bold">Kwitansi Pembayaran Digital</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">No. Kwitansi</p>
                    <p class="text-black font-black text-xl">#KWT-{{ str_pad($pembayaran->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-10 py-10">

            {{-- Info warga & Status --}}
            <div class="flex items-start justify-between mb-8 pb-8 border-b border-gray-200">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Diterima Dari:</p>
                    <p class="font-black text-black text-lg">{{ $pembayaran->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $pembayaran->user->email }}</p>
                    <p class="text-sm text-gray-500 mt-1 italic">{{ $pembayaran->user->alamat ?? 'Alamat tidak terdata' }}</p>
                </div>
                <div class="text-right">
                    <div class="inline-flex items-center gap-1.5 border-2 border-black px-4 py-1.5 font-black text-black text-xs uppercase italic">
                        Status: Lunas / Paid
                    </div>
                    <p class="text-[10px] text-gray-400 mt-3">{{ $pembayaran->updated_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>

            {{-- Detail Tabel --}}
            <table class="w-full mb-10 border-collapse">
                <thead>
                    <tr class="border-b-2 border-black">
                        <th class="py-3 text-left text-xs font-black text-black uppercase tracking-wider">Deskripsi Pembayaran</th>
                        <th class="py-3 text-right text-xs font-black text-black uppercase tracking-wider">Metode</th>
                        <th class="py-3 text-right text-xs font-black text-black uppercase tracking-wider">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-5">
                            <p class="font-bold text-gray-800">Iuran RT - Periode {{ \Carbon\Carbon::parse($pembayaran->iuran->bulan.'-01')->translatedFormat('F Y') }}</p>
                            <p class="text-xs text-gray-400 mt-1 italic">Tercatat pada: {{ $pembayaran->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="py-5 text-right font-medium text-gray-700">
                            {{ strtoupper($pembayaran->provider) }}
                        </td>
                        <td class="py-5 text-right font-black text-black">
                            Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-black">
                        <td colspan="2" class="py-4 text-right text-sm font-black text-black uppercase">Total Terbayar</td>
                        <td class="py-4 text-right text-lg font-black text-black">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            {{-- Signature Area --}}
            <div class="flex justify-between items-start mt-12 gap-12">
                <div class="flex-1 italic text-xs text-gray-500 leading-relaxed max-w-[200px]">
                    * Kwitansi ini sah sebagai bukti pembayaran iuran RT dan diterbitkan secara elektronik oleh sistem Smart Kas RT.
                </div>
                <div class="flex flex-col items-center min-w-[150px]">
                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-4">Bendahara RT</p>
                    <div class="relative py-4">
                        <p class="text-3xl text-black" style="font-family: 'Dancing Script', cursive;">Hendra</p>
                        <div class="absolute -bottom-1 -right-4 italic text-[8px] text-gray-300 transform rotate-12">Digital Signed</div>
                    </div>
                    <div class="w-full border-t border-black pt-2 text-center text-sm font-black text-black">
                        HENDRA
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-10 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <span class="text-[9px] text-gray-400 italic">ID Transaksi: SM-{{ $pembayaran->id }}</span>
            <span class="text-[9px] text-gray-400">Dicetak: {{ now()->format('d M Y, H:i:s') }} WIB</span>
        </div>
    </div>

    <script>
    function downloadPDF() {
        const el = document.getElementById('kwitansiDoc');
        const opt = {
            margin:      [10, 10],
            filename:    'Kwitansi-KWT-{{ str_pad($pembayaran->id, 6, "0", STR_PAD_LEFT) }}.pdf',
            image:       { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF:       { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(el).save();
    }
    </script>
</body>
</html>
