<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'no_transaksi';

    protected $fillable = [
        'order_id',
        'snap_token',
        'tanggal_jual',
        'kode_seni',
        'harga',
        'jumlah',
        'id_pembeli',
        'status',
        'payment_type',
        'paid_at',
        'cart_id'
    ];

    protected $casts = [
        'tanggal_jual' => 'date',
        'paid_at' => 'datetime',
        'harga' => 'decimal:2'
    ];

    // Relasi ke KaryaSeni
    public function karya()
    {
        return $this->belongsTo(KaryaSeni::class, 'kode_seni', 'kode_seni');
    }

    // Relasi ke Pembeli
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli', 'id_pembeli');
    }
}