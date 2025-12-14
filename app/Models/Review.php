<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $primaryKey = 'id_review';
    protected $fillable = ['kode_seni', 'id_user', 'nilai', 'komentar'];

    // Tentukan jika ini incrementing
    public $incrementing = true;
    public $timestamps = true;

    // Relasi ke KaryaSeni
    public function karya()
    {
        return $this->belongsTo(KaryaSeni::class, 'kode_seni', 'kode_seni');
    }

    // Relasi ke Pembeli (mengacu pada id_pembeli)
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_user', 'id_pembeli');
    }

    // Untuk backward compatibility dengan relasi user
    public function user()
    {
        return $this->belongsTo(Pembeli::class, 'id_user', 'id_pembeli');
    }
}