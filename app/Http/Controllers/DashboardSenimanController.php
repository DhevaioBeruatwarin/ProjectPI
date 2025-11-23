<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seniman;
use App\Models\KaryaSeni;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardSenimanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seniman');
    }

    // Dashboard utama
    public function index()
    {
        $seniman = Auth::guard('seniman')->user();
        $karyaSeni = KaryaSeni::latest()->get();

        return view('Seniman.dashboard', compact('seniman', 'karyaSeni'));
    }

    // Edit profil seniman
    public function editProfil()
    {
        $seniman = Auth::guard('seniman')->user();
        return view('Seniman.edit_profil', compact('seniman'));
    }

    public function updateProfil(Request $request)
    {
        $seniman = Auth::guard('seniman')->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $seniman->nama = $request->nama;
        $seniman->bio = $request->bio;

        // Upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($seniman->foto && Storage::disk('public')->exists('foto_seniman/' . $seniman->foto)) {
                Storage::disk('public')->delete('foto_seniman/' . $seniman->foto);
            }

            // Simpan foto baru
            $fotoName = time() . '_' . $seniman->id_seniman . '.' . $request->foto->extension();
            $request->foto->storeAs('public/foto_seniman', $fotoName);
            $seniman->foto = $fotoName;
        }

        $seniman->save();

        return redirect()->route('seniman.dashboard')->with('success', 'Profil berhasil diperbarui!');
    }

    // CRUD karya seni
    public function createKarya()
    {
        return view('Seniman.karya.create');
    }

    public function storeKarya(Request $request)
    {
        $seniman = Auth::guard('seniman')->user();

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Simpan gambar karya
        $gambarName = time() . '_' . $seniman->id_seniman . '.' . $request->gambar->extension();
        $request->gambar->storeAs('public/karya_seni', $gambarName);

        $kodeSeni = 'KS' . time() . rand(100, 999);
        KaryaSeni::create([
            'kode_seni' => $kodeSeni,
            'id_seniman' => $seniman->id_seniman,
            'nama_karya' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'gambar' => $gambarName,
        ]);

        return redirect()->route('seniman.dashboard')->with('success', 'Karya seni berhasil ditambahkan!');
    }

    public function editKarya($kode_seni)
    {
        $seniman = Auth::guard('seniman')->user();
        $karya = KaryaSeni::where('kode_seni', $kode_seni)
            ->where('id_seniman', $seniman->id_seniman)
            ->firstOrFail();

        return view('Seniman.karya.edit', compact('karya'));
    }

    public function updateKarya(Request $request, $kode_seni)
    {
        $seniman = Auth::guard('seniman')->user();
        $karya = KaryaSeni::where('kode_seni', $kode_seni)
            ->where('id_seniman', $seniman->id_seniman)
            ->firstOrFail();

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $karya->nama_karya = $request->judul;
        $karya->deskripsi = $request->deskripsi;
        $karya->harga = $request->harga;
        $karya->stok = $request->stok;

        // Update gambar jika ada file baru
        if ($request->hasFile('gambar')) {
            if ($karya->gambar && Storage::disk('public')->exists('karya_seni/' . $karya->gambar)) {
                Storage::disk('public')->delete('karya_seni/' . $karya->gambar);
            }

            $gambarName = time() . '_' . $karya->kode_seni . '.' . $request->gambar->extension();
            $request->gambar->storeAs('public/karya_seni', $gambarName);
            $karya->gambar = $gambarName;
        }

        $karya->save();

        return redirect()->route('seniman.dashboard')->with('success', 'Karya seni berhasil diperbarui!');
    }

    public function destroyKarya($kode_seni)
    {
        $seniman = Auth::guard('seniman')->user();
        $karya = KaryaSeni::where('kode_seni', $kode_seni)
            ->where('id_seniman', $seniman->id_seniman)
            ->firstOrFail();

        // Hapus file gambar dari storage jika ada
        if ($karya->gambar && Storage::disk('public')->exists('karya_seni/' . $karya->gambar)) {
            Storage::disk('public')->delete('karya_seni/' . $karya->gambar);
        }

        $karya->delete();

        return redirect()->route('seniman.karya.index')->with('success', 'Karya seni berhasil dihapus!');
    }
}