<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BackupManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function __construct(protected BackupManager $backup)
    {
        //
    }

    /** Tampilkan daftar backup. */
    public function index()
    {
        $backups = $this->backup->list();

        return view('admin.backup', compact('backups'));
    }

    /** Trigger backup manual. */
    public function store(): RedirectResponse
    {
        try {
            $this->backup->create();
            $this->backup->prune(30);

            return redirect()->route('admin.backup')
                ->with('success', 'Backup database berhasil dibuat.');
        } catch (\Throwable $e) {
            Log::error('BackupController@store: ' . $e->getMessage());

            return redirect()->route('admin.backup')
                ->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }

    /** Download file backup. */
    public function download(string $filename): Response|RedirectResponse
    {
        // Sanitasi: hanya izinkan nama file tanpa path traversal
        $filename = basename($filename);
        $path     = "backups/{$filename}";

        if (! Storage::disk('local')->exists($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        return Storage::disk('local')->download($path, $filename);
    }
}
