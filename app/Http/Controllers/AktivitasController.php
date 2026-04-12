<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.aktivitas', compact('logs'));
    }

    public function hapusSemua()
    {
        ActivityLog::truncate();
        return back()->with('success', 'Semua log aktivitas berhasil dihapus.');
    }
}
