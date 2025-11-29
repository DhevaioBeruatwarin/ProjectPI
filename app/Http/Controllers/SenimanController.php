<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Seniman;
use App\Models\KaryaSeni;

class SenimanController extends Controller
{
    // Tampilkan profil seniman yang sedang login
    public function profile()
    {
        $seniman = Auth::guard('seniman')->user();

        if (!$seniman) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data fresh dari database
        $seniman = Seniman::find($seniman->id_seniman);

        // Tambahan: agar foto profil bisa muncul di navbar
        session(['seniman_foto' => $seniman->foto]);

        return response()
            ->view('Seniman.profile', compact('seniman'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    // Tampilkan form edit profil
    public function edit()
    {
        $seniman = $this->currentSeniman();

        return view('Seniman.edit_profil', compact('seniman'));
    }

    // Simpan perubahan profil (nama & email tidak bisa diubah)
    public function update(Request $request)
    {
        $seniman = $this->currentSeniman();

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('seniman', 'email')->ignore($seniman->id_seniman, 'id_seniman'),
                Rule::unique('pembeli', 'email'),
                Rule::unique('admin', 'email'),
            ],
            'no_hp' => 'nullable|string|max:20',
            'bidang' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $senimanModel = Seniman::findOrFail($seniman->id_seniman);

            $data = [
                'nama' => $request->nama,
                'email' => $request->email,
                    'no_hp' => $request->no_hp,
                    'bidang' => $request->bidang,
                    'bio' => $request->bio,
                    'alamat' => $request->alamat,
            ];

            if ($request->hasFile('foto')) {
                $data['foto'] = $this->replaceFoto($senimanModel, $request->file('foto'));
            }

            $senimanModel->update($data);

            $senimanFresh = $senimanModel->fresh();
            Auth::guard('seniman')->setUser($senimanFresh);
            session(['seniman_foto' => $senimanFresh->foto]);

            return redirect()->route('seniman.profil')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    // Upload foto profil
    public function updateFoto(Request $request)
    {
        $seniman = $this->currentSeniman();

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'foto.required' => 'Silakan pilih foto',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpeg, png, jpg, atau gif',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        try {
            $senimanModel = Seniman::findOrFail($seniman->id_seniman);
            $filename = $this->replaceFoto($senimanModel, $request->file('foto'));

            $senimanModel->update(['foto' => $filename]);
            $senimanFresh = $senimanModel->fresh();
            Auth::guard('seniman')->setUser($senimanFresh);
            session(['seniman_foto' => $filename]);

            return redirect()
                ->route('seniman.profil')
                ->with('success', 'Foto profil berhasil diperbarui!')
                ->with('_timestamp', time());
        } catch (\Exception $e) {
            return redirect()->route('seniman.profil')->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
        }
    }

    public function karyaSaya()
    {
        $seniman = auth()->guard('seniman')->user();
        $karya = KaryaSeni::where('id_seniman', $seniman->id_seniman)->get();
        return view('Seniman.karya.index', compact('karya'));
    }

    private function currentSeniman(): Seniman
    {
        $seniman = Auth::guard('seniman')->user();

        if (!$seniman) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        return Seniman::findOrFail($seniman->id_seniman);
    }

    private function replaceFoto(Seniman $seniman, UploadedFile $file): string
    {
        if ($seniman->foto && Storage::disk('public')->exists('foto_seniman/' . $seniman->foto)) {
            Storage::disk('public')->delete('foto_seniman/' . $seniman->foto);
        }

        $filename = time() . '_' . $seniman->id_seniman . '.' . $file->getClientOriginalExtension();
        $file->storeAs('foto_seniman', $filename, 'public');

        return $filename;
    }

}
