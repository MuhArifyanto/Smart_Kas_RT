<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesanController extends Controller
{
    // ────────────────────────────────────────────────
    // WARGA: halaman chat 1-on-1 dengan admin
    // ────────────────────────────────────────────────

    public function wargaIndex()
    {
        $user  = Auth::user();
        $admin = User::where('role', 'admin')->first();

        if (! $admin) {
            return view('warga.chat', ['messages' => collect(), 'admin' => null]);
        }

        // Tandai pesan dari admin sebagai dibaca
        Pesan::where('pengirim_id', $admin->id)
            ->where('penerima_id', $user->id)
            ->whereNull('dibaca_at')
            ->update(['dibaca_at' => now()]);

        $messages = Pesan::where(function ($q) use ($user, $admin) {
            $q->where('pengirim_id', $user->id)->where('penerima_id', $admin->id);
        })->orWhere(function ($q) use ($user, $admin) {
            $q->where('pengirim_id', $admin->id)->where('penerima_id', $user->id);
        })->with(['pengirim:id,name,role,avatar'])
          ->orderBy('created_at', 'asc')
          ->get();

        return view('warga.chat', compact('messages', 'admin', 'user'));
    }

    // ────────────────────────────────────────────────
    // ADMIN: inbox semua percakapan warga
    // ────────────────────────────────────────────────

    public function adminIndex()
    {
        $admin = Auth::user();

        // Ambil semua warga yang punya percakapan dengan admin
        $conversationUserIds = Pesan::where(function ($q) use ($admin) {
            $q->where('pengirim_id', $admin->id)->orWhere('penerima_id', $admin->id);
        })
        ->selectRaw('IF(pengirim_id = ?, penerima_id, pengirim_id) as other_user_id', [$admin->id])
        ->distinct()
        ->pluck('other_user_id');

        // Ambil data warga beserta pesan terakhir & unread count
        $conversations = User::whereIn('id', $conversationUserIds)
            ->where('role', 'warga')
            ->get()
            ->map(function ($warga) use ($admin) {
                $lastMsg = Pesan::where(function ($q) use ($warga, $admin) {
                    $q->where('pengirim_id', $warga->id)->where('penerima_id', $admin->id);
                })->orWhere(function ($q) use ($warga, $admin) {
                    $q->where('pengirim_id', $admin->id)->where('penerima_id', $warga->id);
                })->latest()->first();

                $unread = Pesan::where('pengirim_id', $warga->id)
                    ->where('penerima_id', $admin->id)
                    ->whereNull('dibaca_at')
                    ->count();

                $warga->last_message   = $lastMsg;
                $warga->unread_count   = $unread;
                return $warga;
            })
            ->sortByDesc(fn($w) => optional($w->last_message)->created_at)
            ->values();

        // Jika ada ?warga_id maka tampilkan percakapan detail
        $activeWarga    = null;
        $activeMessages = collect();

        if (request('warga_id')) {
            $activeWarga = User::find(request('warga_id'));
            if ($activeWarga) {
                // Tandai pesan sebagai dibaca
                Pesan::where('pengirim_id', $activeWarga->id)
                    ->where('penerima_id', $admin->id)
                    ->whereNull('dibaca_at')
                    ->update(['dibaca_at' => now()]);

                $activeMessages = Pesan::where(function ($q) use ($activeWarga, $admin) {
                    $q->where('pengirim_id', $activeWarga->id)->where('penerima_id', $admin->id);
                })->orWhere(function ($q) use ($activeWarga, $admin) {
                    $q->where('pengirim_id', $admin->id)->where('penerima_id', $activeWarga->id);
                })->with(['pengirim:id,name,role,avatar'])
                  ->orderBy('created_at', 'asc')
                  ->get();
            }
        }

        return view('admin.chat', compact('conversations', 'activeWarga', 'activeMessages', 'admin'));
    }

    // ────────────────────────────────────────────────
    // KIRIM PESAN (warga → admin, admin → warga)
    // ────────────────────────────────────────────────

    public function store(Request $request, User $user = null)
    {
        $request->validate([
            'isi_pesan' => 'nullable|string|max:2000',
            'file'      => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480', // 20MB
        ]);

        if (!$request->isi_pesan && !$request->hasFile('file')) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Pesan atau file harus diisi.'], 422);
            }
            return back()->with('error', 'Pesan atau file harus diisi.');
        }

        $pengirim = Auth::user();
        $penerima = null;

        // Tentukan penerima
        if ($pengirim->role === 'admin') {
            // Admin kirim ke warga (bisa lewat parameter {user} atau penerima_id di request)
            $penerimaId = $user ? $user->id : $request->penerima_id;
            
            if (!$penerimaId) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Penerima tidak ditentukan.'], 422);
                }
                return back()->with('error', 'Penerima tidak ditentukan.');
            }

            $penerima = User::find($penerimaId);
            if (!$penerima) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Warga tidak ditemukan.'], 422);
                }
                return back()->with('error', 'Warga tidak ditemukan.');
            }
        } else {
            // Warga kirim ke admin (selalu ambil admin pertama)
            $penerima = User::where('role', 'admin')->first();
            if (!$penerima) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Admin tidak tersedia saat ini.'], 422);
                }
                return back()->with('error', 'Admin tidak ditemukan.');
            }
        }

        $filePath = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext  = strtolower($file->getClientOriginalExtension());
            
            if (in_array($ext, ['jpg','jpeg','png'])) {
                $fileType = 'image';
            } elseif (in_array($ext, ['mp4','mov','avi'])) {
                $fileType = 'video';
            }

            $path = $file->store('chat_files', 'public');
            $filePath = $path;
        }

        Pesan::create([
            'pengirim_id' => $pengirim->id,
            'penerima_id' => $penerima->id,
            'isi_pesan'   => $request->isi_pesan,
            'file_path'   => $filePath,
            'file_type'   => $fileType,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['status' => 'ok']);
        }

        if ($pengirim->role === 'warga') {
            return redirect()->route('warga.chat');
        }

        return redirect()->route('admin.chat', ['warga_id' => $penerima->id]);
    }

    // ────────────────────────────────────────────────
    // API: jumlah pesan belum dibaca (untuk badge sidebar)
    // ────────────────────────────────────────────────

    public function unreadCount()
    {
        $user   = Auth::user();
        $count  = Pesan::where('penerima_id', $user->id)
            ->whereNull('dibaca_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    // ────────────────────────────────────────────────
    // AJAX: ambil pesan terbaru (polling)
    // ────────────────────────────────────────────────

    public function poll(Request $request)
    {
        $user      = Auth::user();
        $afterId   = (int) $request->get('after_id', 0);

        if ($user->role === 'warga') {
            $admin = User::where('role', 'admin')->first();
            if (! $admin) return response()->json([]);

            $messages = Pesan::where(function ($q) use ($user, $admin) {
                $q->where('pengirim_id', $user->id)->where('penerima_id', $admin->id);
            })->orWhere(function ($q) use ($user, $admin) {
                $q->where('pengirim_id', $admin->id)->where('penerima_id', $user->id);
            })->where('id', '>', $afterId)
              ->with('pengirim:id,name,role')
              ->orderBy('created_at', 'asc')
              ->get();

            // Tandai pesan baru dari admin sebagai dibaca
            Pesan::where('pengirim_id', $admin->id)
                ->where('penerima_id', $user->id)
                ->where('id', '>', $afterId)
                ->whereNull('dibaca_at')
                ->update(['dibaca_at' => now()]);
        } else {
            // Admin polling percakapan tertentu
            $request->validate(['warga_id' => 'required|exists:users,id']);
            $warga = User::where('id', $request->warga_id)->where('role', 'warga')->first();

            if (!$warga) return response()->json([], 404);

            $messages = Pesan::where(function ($q) use ($warga, $user) {
                $q->where('pengirim_id', $warga->id)->where('penerima_id', $user->id);
            })->orWhere(function ($q) use ($warga, $user) {
                $q->where('pengirim_id', $user->id)->where('penerima_id', $warga->id);
            })->where('id', '>', $afterId)
              ->with('pengirim:id,name,role')
              ->orderBy('created_at', 'asc')
              ->get();

            // Tandai dibaca
            Pesan::where('pengirim_id', $warga->id)
                ->where('penerima_id', $user->id)
                ->where('id', '>', $afterId)
                ->whereNull('dibaca_at')
                ->update(['dibaca_at' => now()]);
        }

        $messages = $messages->map(fn($m) => [
            'id'         => $m->id,
            'isi_pesan'  => $m->isi_pesan,
            'file_path'  => $m->file_path ? '/storage/'.$m->file_path : null,
            'file_type'  => $m->file_type,
            'is_mine'    => $m->pengirim_id === $user->id,
            'waktu'      => $m->created_at->translatedFormat('H:i'),
            'nama'       => $m->pengirim->name ?? '-',
        ]);

        // Tambahkan status online lawan bicara
        $partnerStatus = "Offline";
        $isPartnerOnline = false;

        if ($user->role === 'warga') {
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $partnerStatus = $admin->lastSeenStatus();
                $isPartnerOnline = $admin->isOnline();
            }
        } else {
            $warga = User::find($request->warga_id);
            if ($warga) {
                $partnerStatus = $warga->lastSeenStatus();
                $isPartnerOnline = $warga->isOnline();
            }
        }

        return response()->json([
            'messages' => $messages,
            'partner_status' => $partnerStatus,
            'is_partner_online' => $isPartnerOnline,
        ]);
    }
}

