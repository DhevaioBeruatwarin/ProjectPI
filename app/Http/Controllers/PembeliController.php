<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Pembeli;

class PembeliController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pembeli');
    }

    // Tampilkan profil pembeli yang sedang login
    public function profil()
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data fresh dari database
        $pembeli = Pembeli::find($pembeli->id_pembeli);

        // Set header untuk mencegah cache
        return response()
            ->view('pembeli.profile', compact('pembeli'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    // Tampilkan form edit profil
    public function edit()
    {
        $pembeli = $this->currentPembeli();

        return view('pembeli.edit_profil', compact('pembeli'));
    }

    // Simpan perubahan profil
    public function update(Request $request)
    {
        $pembeli = $this->currentPembeli();

        $request->validate([
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $pembeliModel = Pembeli::findOrFail($pembeli->id_pembeli);

            $data = [
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'bio' => $request->bio,
            ];

            if ($request->hasFile('foto')) {
                $data['foto'] = $this->replaceFoto($pembeliModel, $request->file('foto'));
            }

            $pembeliModel->update($data);

            Auth::guard('pembeli')->setUser($pembeliModel->fresh());

            return redirect()->route('pembeli.profil')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }


    // Upload foto profil
    public function updateFoto(Request $request)
    {
        $pembeli = $this->currentPembeli();

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'foto.required' => 'Silakan pilih foto',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpeg, png, jpg, atau gif',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        try {
            $pembeliModel = Pembeli::findOrFail($pembeli->id_pembeli);
            $filename = $this->replaceFoto($pembeliModel, $request->file('foto'));

            $pembeliModel->update(['foto' => $filename]);
            Auth::guard('pembeli')->setUser($pembeliModel->fresh());

            return redirect()->route('pembeli.profil')->with('success', 'Foto profil berhasil diperbarui!')->with('_timestamp', time());
        } catch (\Exception $e) {
            return redirect()->route('pembeli.profil')->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
        }
    }

    private function currentPembeli(): Pembeli
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        return Pembeli::findOrFail($pembeli->id_pembeli);
    }

    private function replaceFoto(Pembeli $pembeli, UploadedFile $file): string
    {
        if ($pembeli->foto && Storage::disk('public')->exists('foto_pembeli/' . $pembeli->foto)) {
            Storage::disk('public')->delete('foto_pembeli/' . $pembeli->foto);
        }

        $filename = time() . '_' . $pembeli->id_pembeli . '.' . $file->getClientOriginalExtension();
        $file->storeAs('foto_pembeli', $filename, 'public');

        return $filename;
    }
}
