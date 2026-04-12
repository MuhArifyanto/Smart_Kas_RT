<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluaran     = Pengeluaran::latest('tanggal')->paginate(10);
        $totalPengeluaran = Pengeluaran::sum('nominal');
        return view('admin.pengeluaran', compact('pengeluaran', 'totalPengeluaran'));
    }

    public function store(Request $request)
    {
        \App\Models\Pengeluaran::create($request->only('keterangan', 'nominal', 'tanggal'));
        
        \App\Models\ActivityLog::catat('pengeluaran_baru', 'Admin mencatat pengeluaran: ' . $request->keterangan . ' (Rp ' . number_format($request->nominal, 0, ',', '.') . ')', 'red', 'minus');

        return back()->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $pengeluaran->update($request->only('keterangan', 'nominal', 'tanggal'));

        \App\Models\ActivityLog::catat('edit_pengeluaran', 'Admin mengubah data pengeluaran: ' . $pengeluaran->keterangan, 'blue', 'edit');

        return back()->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $ket = $pengeluaran->keterangan;
        $pengeluaran->delete();

        \App\Models\ActivityLog::catat('hapus_pengeluaran', 'Admin menghapus data pengeluaran (' . $ket . ')', 'red', 'trash');

        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
