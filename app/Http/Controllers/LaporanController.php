<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = $request->get('tahun', now()->format('Y'));
        $periode = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);

        // Pemasukan: pembayaran disetujui bulan ini
        $pemasukan = Pembayaran::with('user')
            ->where('status', 'disetujui')
            ->whereYear('dibayar_at', $tahun)
            ->whereMonth('dibayar_at', $bulan)
            ->latest('dibayar_at')
            ->get();

        // Pengeluaran bulan ini
        $pengeluaran = Pengeluaran::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->latest('tanggal')
            ->get();

        $totalPemasukan  = $pemasukan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('nominal');
        $saldoAkhir      = $totalPemasukan - $totalPengeluaran;

        return view('admin.laporan', compact(
            'pemasukan', 'pengeluaran',
            'totalPemasukan', 'totalPengeluaran', 'saldoAkhir',
            'bulan', 'tahun'
        ));
    }

    // Export CSV (bisa dibuka di Excel)
    public function exportExcel(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = $request->get('tahun', now()->format('Y'));

        $pemasukan   = Pembayaran::with('user')->where('status', 'disetujui')
            ->whereYear('dibayar_at', $tahun)->whereMonth('dibayar_at', $bulan)
            ->latest('dibayar_at')->get();
        $pengeluaran = Pengeluaran::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)->latest('tanggal')->get();

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');
        $filename  = "Laporan-Keuangan-{$namaBulan}.csv";

        $rows = [];
        $rows[] = ["LAPORAN KEUANGAN RT - {$namaBulan}"];
        $rows[] = [];
        $rows[] = ["PEMASUKAN"];
        $rows[] = ["Tanggal", "Keterangan", "Nominal"];
        foreach ($pemasukan as $p) {
            $rows[] = [
                Carbon::parse($p->dibayar_at)->format('d M Y'),
                "Iuran " . ($p->user->name ?? '-'),
                $p->jumlah,
            ];
        }
        $rows[] = ["Total Pemasukan", "", $pemasukan->sum('jumlah')];
        $rows[] = [];
        $rows[] = ["PENGELUARAN"];
        $rows[] = ["Tanggal", "Keterangan", "Nominal"];
        foreach ($pengeluaran as $p) {
            $rows[] = [Carbon::parse($p->tanggal)->format('d M Y'), $p->keterangan, $p->nominal];
        }
        $rows[] = ["Total Pengeluaran", "", $pengeluaran->sum('nominal')];
        $rows[] = [];
        $rows[] = ["SALDO AKHIR", "", $pemasukan->sum('jumlah') - $pengeluaran->sum('nominal')];

        $output = fopen('php://temp', 'r+');
        foreach ($rows as $row) {
            fputcsv($output, $row, ';');
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv, 200, [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    // Export PDF via print view
    public function exportPdf(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = $request->get('tahun', now()->format('Y'));

        $pemasukan   = Pembayaran::with('user')->where('status', 'disetujui')
            ->whereYear('dibayar_at', $tahun)->whereMonth('dibayar_at', $bulan)
            ->latest('dibayar_at')->get();
        $pengeluaran = Pengeluaran::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)->latest('tanggal')->get();

        $totalPemasukan   = $pemasukan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('nominal');
        $saldoAkhir       = $totalPemasukan - $totalPengeluaran;

        return view('admin.laporan-pdf', compact(
            'pemasukan', 'pengeluaran',
            'totalPemasukan', 'totalPengeluaran', 'saldoAkhir',
            'bulan', 'tahun'
        ));
    }
}
