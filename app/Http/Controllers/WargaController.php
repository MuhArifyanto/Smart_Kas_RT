<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'warga');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('alamat', 'like', '%' . $request->search . '%')
                  ->orWhere('no_hp', 'like', '%' . $request->search . '%');
            });
        }

        $warga = $query->latest()->paginate(10)->withQueryString();

        return view('admin.warga', compact('warga'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email',
            'alamat' => 'required|string|max:255',
            'no_hp'  => 'required|string|max:20',
            'status' => 'in:aktif,nonaktif',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->no_hp), // default password = no_hp
            'role'     => 'warga',
            'alamat'   => $request->alamat,
            'no_hp'    => $request->no_hp,
            'status'   => $request->status ?? 'aktif',
        ]);

        \App\Models\ActivityLog::catat('tambah_warga', 'Admin menambahkan warga baru (' . $request->name . ')', 'purple', 'user-plus');

        return back()->with('success', 'Data warga berhasil ditambahkan.');
    }

    public function update(Request $request, User $warga)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $warga->id,
            'alamat' => 'required|string|max:255',
            'no_hp'  => 'required|string|max:20',
            'status' => 'in:aktif,nonaktif',
        ]);

        $warga->update($request->only('name', 'email', 'alamat', 'no_hp', 'status'));

        \App\Models\ActivityLog::catat('edit_warga', 'Admin mengubah data warga (' . $warga->name . ')', 'blue', 'edit');

        return back()->with('success', 'Data warga berhasil diperbarui.');
    }

    public function destroy(User $warga)
    {
        $nama = $warga->name;
        $warga->delete();

        \App\Models\ActivityLog::catat('hapus_warga', 'Admin menghapus data warga (' . $nama . ')', 'red', 'user-minus');

        return back()->with('success', 'Data warga berhasil dihapus.');
    }
}
