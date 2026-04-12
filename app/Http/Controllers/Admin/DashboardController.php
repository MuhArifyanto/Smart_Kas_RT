<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulan = now()->format('Y-m');
        $tahun = now()->year;

        // Stats cards — semua dari DB
        $pemasukanBulan   = Pembayaran::where('status', 'disetujui')
            ->whereYear('dibayar_at', $tahun)
            ->whereMonth('dibayar_at', now()->month)
            ->sum('jumlah');

        $pengeluaranBulan = Pengeluaran::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', now()->month)
            ->sum('nominal');

        $totalPemasukan   = Pembayaran::where('status', 'disetujui')->sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('nominal');
        $totalSaldo       = $totalPemasukan - $totalPengeluaran;

        $stats = [
            'total_saldo'       => $totalSaldo,
            'jumlah_warga'      => User::where('role', 'warga')->count(),
            'pemasukan_bulan'   => $pemasukanBulan,
            'pengeluaran_bulan' => $pengeluaranBulan,
        ];

        // Pie chart: status iuran bulan ini
        $iuranBulanIni = Iuran::where('bulan', $bulan);
        $totalIuran    = $iuranBulanIni->count();
        $lunas         = (clone $iuranBulanIni)->where('status', 'lunas')->count();
        $menunggu      = (clone $iuranBulanIni)->where('status', 'menunggu')->count();
        $belumBayar    = (clone $iuranBulanIni)->where('status', 'belum_bayar')->count();

        $pieData = [
            'lunas'      => $lunas,
            'menunggu'   => $menunggu,
            'belum_bayar'=> $belumBayar,
            'total'      => $totalIuran,
            'persen_lunas'      => $totalIuran > 0 ? round($lunas / $totalIuran * 100) : 0,
            'persen_menunggu'   => $totalIuran > 0 ? round($menunggu / $totalIuran * 100) : 0,
            'persen_belum'      => $totalIuran > 0 ? round($belumBayar / $totalIuran * 100) : 0,
        ];

        // Chart 6 bulan: pemasukan & pengeluaran per bulan
        // OPTIMASI: Panggil DB sekali saja (Fix N+1)
        $enamBulanLalu = now()->subMonths(5)->startOfMonth();
        
        $rawPemasukan = Pembayaran::selectRaw('SUM(jumlah) as total, DATE_FORMAT(dibayar_at, "%Y-%m") as bulan')
            ->where('status', 'disetujui')
            ->where('dibayar_at', '>=', $enamBulanLalu)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $rawPengeluaran = Pengeluaran::selectRaw('SUM(nominal) as total, DATE_FORMAT(tanggal, "%Y-%m") as bulan')
            ->where('tanggal', '>=', $enamBulanLalu)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $chartLabels      = [];
        $chartPemasukan   = [];
        $chartPengeluaran = [];

        for ($i = 5; $i >= 0; $i--) {
            $tgl   = now()->subMonths($i);
            $key   = $tgl->format('Y-m');
            $label = $tgl->translatedFormat('M');
            
            $chartLabels[]      = $label;
            $chartPemasukan[]   = $rawPemasukan[$key] ?? 0;
            $chartPengeluaran[] = $rawPengeluaran[$key] ?? 0;
        }

        // Analitik Tambahan
        $savingsRate = $totalPemasukan > 0 
            ? round(($totalSaldo / $totalPemasukan) * 100, 1) 
            : 0;

        $topExpenses = Pengeluaran::orderByDesc('nominal')
            ->whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->limit(5)
            ->get();

        // Smart insights dari data real
        $persen_belum  = $pieData['persen_belum'];
        $bulanLalu     = now()->subMonth();
        $pemasukanLalu = $rawPemasukan[$bulanLalu->format('Y-m')] ?? 0;
        $pengeluaranLalu = $rawPengeluaran[$bulanLalu->format('Y-m')] ?? 0;

        $selisihPemasukan   = $pemasukanLalu > 0
            ? round(($pemasukanBulan - $pemasukanLalu) / $pemasukanLalu * 100)
            : 0;
        $selisihPengeluaran = $pengeluaranLalu > 0
            ? round(($pengeluaranBulan - $pengeluaranLalu) / $pengeluaranLalu * 100)
            : 0;

        $rataRataPengeluaran = Pengeluaran::selectRaw('AVG(monthly) as avg')
            ->fromSub(
                Pengeluaran::selectRaw('SUM(nominal) as monthly, YEAR(tanggal) as y, MONTH(tanggal) as m')
                    ->groupBy('y', 'm'),
                'monthly_totals'
            )->value('avg') ?? 0;

        $bulanOperasional = $rataRataPengeluaran > 0
            ? floor($totalSaldo / $rataRataPengeluaran)
            : 0;

        $insights = compact(
            'persen_belum', 'belumBayar',
            'selisihPemasukan', 'selisihPengeluaran',
            'pemasukanBulan', 'pengeluaranBulan',
            'pengeluaranLalu', 'bulanOperasional', 'totalSaldo',
            'savingsRate'
        );

        return view('admin.dashboard', compact(
            'stats', 'pieData',
            'chartLabels', 'chartPemasukan', 'chartPengeluaran',
            'insights'
        ));
    }
}
