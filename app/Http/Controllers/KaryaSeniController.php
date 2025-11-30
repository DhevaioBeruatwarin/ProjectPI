<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KaryaSeni;
use Illuminate\Support\Facades\Auth;

class KaryaSeniController extends Controller
{
    // Tampilkan detail karya untuk PEMBELI
    public function showForPembeli($kode_seni)
    {
        // Hanya untuk pembeli
        $karya = KaryaSeni::with('seniman')->where('kode_seni', $kode_seni)->firstOrFail();

        $averageRating = null;
        if ($karya->reviews && $karya->reviews->count() > 0) {
            $averageRating = round($karya->reviews->avg('nilai'), 1);
        }

        $karyaSeniman = KaryaSeni::where('id_seniman', $karya->id_seniman)
            ->where('kode_seni', '!=', $kode_seni)
            ->limit(4)
            ->get();

        $karyaLainnya = KaryaSeni::where('kode_seni', '!=', $kode_seni)
            ->latest()
            ->limit(8)
            ->get();

        return view('Seniman.detail_karya', compact('karya', 'karyaSeniman', 'karyaLainnya', 'averageRating'));
    }

    // Tampilkan detail karya untuk SENIMAN
    public function showForSeniman($kode_seni)
    {
        // Hanya untuk seniman
        $karya = KaryaSeni::with('seniman')->where('kode_seni', $kode_seni)->firstOrFail();

        $averageRating = null;
        if ($karya->reviews && $karya->reviews->count() > 0) {
            $averageRating = round($karya->reviews->avg('nilai'), 1);
        }

        return view('Seniman.detail', compact('karya', 'averageRating'));
    }

    // Dashboard seniman
    public function index()
    {
        $seniman = Auth::guard('seniman')->user();
        $karya = KaryaSeni::where('id_seniman', $seniman->id_seniman)->get();

        return view('Seniman.dashboard', compact('karya', 'seniman'));
    }

    // Tambah karya baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $seniman = Auth::guard('seniman')->user();

        // Simpan file gambar ke storage/public/karya_seni
        $path = $request->file('gambar')->store('karya_seni', 'public');

        // Buat kode karya unik
        $kodeSeni = 'KS' . time() . rand(100, 999);

        // Simpan ke database
        KaryaSeni::create([
            'kode_seni' => $kodeSeni,
            'id_seniman' => $seniman->id_seniman,
            'nama_karya' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'gambar' => basename($path),
        ]);

        return redirect()->route('seniman.dashboard')->with('success', 'Karya berhasil diupload!');
    }

    // Hapus karya
    public function destroy($kode_seni)
    {
        $karya = KaryaSeni::where('kode_seni', $kode_seni)->firstOrFail();
        $karya->delete();

        return redirect()->route('seniman.dashboard')->with('success', 'Karya berhasil dihapus!');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $userSeniman = Auth::guard('seniman')->user();
        $userPembeli = Auth::guard('pembeli')->user();

        if ($userPembeli) {
            // Jika pembeli yang login → cari semua karya
            $karyaSeni = KaryaSeni::where('nama_karya', 'like', "%{$query}%")
                ->orWhere('deskripsi', 'like', "%{$query}%")
                ->orWhere('kode_seni', 'like', "%{$query}%")
                ->get();

            return view('dashboard', compact('karyaSeni', 'query'));
        } elseif ($userSeniman) {
            // Jika seniman yang login → hanya cari di karya miliknya
            $karyaSeni = KaryaSeni::where('id_seniman', $userSeniman->id_seniman)
                ->where(function ($q) use ($query) {
                    $q->where('nama_karya', 'like', "%{$query}%")
                        ->orWhere('deskripsi', 'like', "%{$query}%")
                        ->orWhere('kode_seni', 'like', "%{$query}%");
                })
                ->get();

            $seniman = $userSeniman;
            return view('Seniman.dashboard', compact('karyaSeni', 'query', 'seniman'));
        }

        // Jika belum login
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }
}