<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;
use App\Models\Seniman;
use App\Models\Pembeli;
use App\Models\KaryaSeni;

class AdminController extends Controller
{
    // ======= LOGIN LOGIC =======
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout');
    }

    // ======= DASHBOARD =======
    public function dashboard()
    {
        $jumlahSeniman = Seniman::count();
        $jumlahPembeli = Pembeli::count();
        $jumlahKarya = KaryaSeni::count();

        return view('admin.dashboard', compact('jumlahSeniman','jumlahPembeli','jumlahKarya'));
    }

    // ======= PROFIL =======
    public function profil()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfil(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'nama_admin' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $admin->nama_admin = $request->nama_admin;
        $admin->email = $request->email;
        $admin->save();

        return redirect()->route('admin.profil')->with('success','Profil berhasil diperbarui!');
    }

    public function updateFoto(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($admin->foto && Storage::disk('public')->exists('foto_admin/'.$admin->foto)) {
            Storage::disk('public')->delete('foto_admin/'.$admin->foto);
        }

        $namaFoto = time().'_'.$admin->id_admin.'.'.$request->foto->extension();
        $request->foto->storeAs('public/foto_admin', $namaFoto);

        $admin->foto = $namaFoto;
        $admin->save();

        return redirect()->route('admin.profil')->with('success','Foto profil berhasil diperbarui!');
    }

    // ======= CRUD SENIMAN =======
    public function kelolaSeniman()
    {
        $seniman = Seniman::all();
        return view('admin.seniman', compact('seniman'));
    }

    public function hapusSeniman($id)
    {
        Seniman::destroy($id);
        return back()->with('success','Data seniman berhasil dihapus.');
    }

    // ======= CRUD KARYA =======
    public function kelolaKarya()
    {
        $karya = KaryaSeni::all();
        return view('admin.karya', compact('karya'));
    }

    public function hapusKarya($kode_seni)
    {
        KaryaSeni::where('kode_seni',$kode_seni)->delete();
        return back()->with('success','Data karya berhasil dihapus.');
    }

    // ======= CRUD PEMBELI =======
    public function kelolaPembeli()
    {
        $pembeli = Pembeli::all();
        return view('admin.pembeli', compact('pembeli'));
    }

    public function hapusPembeli($id_pembeli)
    {
        Pembeli::destroy($id_pembeli);
        return back()->with('success','Data pembeli berhasil dihapus.');
    }
}
