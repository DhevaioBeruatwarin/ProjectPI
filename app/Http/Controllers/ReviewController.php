<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\KaryaSeni;
use App\Models\Pembeli;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Store new review
    public function store(Request $request, $kode_seni)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ]);

        // Cek apakah user sudah membeli karya ini
        $user = Auth::guard('pembeli')->user();

        // Optional: Tambahkan validasi apakah user sudah membeli karya ini
        // $hasPurchased = ... // Logic untuk cek purchase

        $review = Review::create([
            'kode_seni' => $kode_seni,
            'id_user' => $user->id_pembeli,
            'nilai' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil ditambahkan!',
            'review' => $review,
            'average_rating' => KaryaSeni::where('kode_seni', $kode_seni)->first()->averageRating()
        ]);
    }

    // Update review
    public function update(Request $request, $id_review)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ]);

        $review = Review::findOrFail($id_review);

        // Authorization check
        if (Auth::guard('pembeli')->user()->id_pembeli != $review->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit review ini.'
            ], 403);
        }

        $review->update([
            'nilai' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil diperbarui!',
            'review' => $review
        ]);
    }

    // Delete review
    public function destroy($id_review)
    {
        $review = Review::findOrFail($id_review);

        // Authorization check
        if (Auth::guard('pembeli')->user()->id_pembeli != $review->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus review ini.'
            ], 403);
        }

        $kode_seni = $review->kode_seni;
        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil dihapus!',
            'average_rating' => KaryaSeni::where('kode_seni', $kode_seni)->first()->averageRating()
        ]);
    }

    // Get reviews for a karya
    public function getReviews($kode_seni)
    {
        $reviews = Review::with([
            'pembeli' => function ($query) {
                $query->select('id_pembeli', 'nama', 'foto');
            }
        ])
            ->where('kode_seni', $kode_seni)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'reviews' => $reviews,
            'total' => $reviews->count(),
            'average_rating' => KaryaSeni::where('kode_seni', $kode_seni)->first()->averageRating()
        ]);
    }

    // Check if user has reviewed
    public function checkUserReview($kode_seni)
    {
        $user = Auth::guard('pembeli')->user();

        if (!$user) {
            return response()->json([
                'has_reviewed' => false
            ]);
        }

        $review = Review::where('kode_seni', $kode_seni)
            ->where('id_user', $user->id_pembeli)
            ->first();

        return response()->json([
            'has_reviewed' => !is_null($review),
            'review' => $review
        ]);
    }

    /**
     * Seniman menanggapi review pembeli
     */
    /**
     * Seniman menanggapi review pembeli (menggunakan tabel terpisah)
     */
    public function replyAsSeniman(Request $request, $id_review)
    {
        $request->validate([
            'tanggapan' => 'required|string|max:2000',
        ]);

        $seniman = Auth::guard('seniman')->user();
        if (!$seniman) {
            abort(403, 'Hanya seniman yang bisa menanggapi review.');
        }

        $review = Review::with('karya')->findOrFail($id_review);

        // Pastikan karya milik seniman yang login
        if (!$review->karya || $review->karya->id_seniman !== $seniman->id_seniman) {
            abort(403, 'Anda tidak berhak menanggapi review ini.');
        }

        // Cek apakah sudah ada response sebelumnya
        $existingResponse = \App\Models\ReviewResponse::where('id_review', $id_review)
            ->where('id_seniman', $seniman->id_seniman)
            ->first();

        if ($existingResponse) {
            // Update response yang sudah ada
            $existingResponse->update([
                'tanggapan' => $request->tanggapan
            ]);
            $message = 'Tanggapan berhasil diperbarui!';
        } else {
            // Buat response baru
            \App\Models\ReviewResponse::create([
                'id_review' => $id_review,
                'id_seniman' => $seniman->id_seniman,
                'tanggapan' => $request->tanggapan,
            ]);
            $message = 'Tanggapan berhasil dikirim!';
        }

        return redirect()
            ->route('seniman.karya.detail', ['kode_seni' => $review->kode_seni])
            ->with('success', $message);
    }
}