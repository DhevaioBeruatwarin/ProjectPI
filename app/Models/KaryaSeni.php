<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryaSeni extends Model
{
    use HasFactory;

    protected $table = 'karya_seni';
    protected $primaryKey = 'kode_seni';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_seni',
        'nama_karya',
        'harga',
        'deskripsi',
        'id_seniman',
        'stok',
        'terjual',
        'gambar'
    ];

    public function seniman()
    {
        return $this->belongsTo(Seniman::class, 'id_seniman');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'kode_seni', 'kode_seni');
    }

    public function averageRating()
    {
        return $this->reviews()->avg('nilai');
    }
}
