<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SystemController extends Controller
{
    public function fixStorage()
    {
        try {
            // 1. Hapus link lama jika ada (untuk menghindari error 'link already exists')
            $linkPath = public_path('storage');
            if (file_exists($linkPath)) {
                // Gunakan rmdir untuk Windows junction/symlink directory
                if (is_link($linkPath) || is_dir($linkPath)) {
                    @unlink($linkPath); // Coba unlink dulu
                    if (file_exists($linkPath)) {
                        @rmdir($linkPath); // Jika masih ada, coba rmdir
                    }
                }
            }

            // 2. Create storage link baru
            Artisan::call('storage:link');
            
            // 3. Clear config & cache secara menyeluruh
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            return back()->with('success', 'Sistem berhasil diperbarui: Link storage disinkronkan & cache dibersihkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui sistem: ' . $e->getMessage());
        }
    }

    public function checkStorage()
    {
        $results = [
            'public_storage_exists' => file_exists(public_path('storage')),
            'storage_app_public_exists' => file_exists(storage_path('app/public')),
            'app_url' => config('app.url'),
            'filesystem_driver' => config('filesystems.default'),
        ];
        
        return response()->json($results);
    }
}
