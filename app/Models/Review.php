<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KaryaSeni;
use App\Models\Pembeli;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $primaryKey = 'id_review';
    protected $fillable = ['kode_seni', 'id_user', 'nilai', 'komentar'];

    /**
     * Relasi ke karya seni
     */
    public function karya()
    {
        return $this->belongsTo(KaryaSeni::class, 'kode_seni', 'kode_seni');
    }

    /**
     * Relasi ke pembeli yang memberi review
     */
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_user', 'id_pembeli');
    }

    /**
     * Alias user untuk backward compatibility
     */
    public function user()
    {
        return $this->belongsTo(Pembeli::class, 'id_user', 'id_pembeli');
    }

    // Tambahkan relasi ke responses
    public function responses()
    {
        return $this->hasMany(ReviewResponse::class, 'id_review', 'id_review');
    }

    // Helper untuk mengambil tanggapan terbaru
    public function latestResponse()
    {
        return $this->hasOne(ReviewResponse::class, 'id_review', 'id_review')
            ->latest();
    }
}