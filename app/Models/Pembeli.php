<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pembeli extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'pembeli';
    protected $primaryKey = 'id_pembeli';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'alamat',
        'no_hp',
        'bio',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'pembeli_id', 'id_pembeli');
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'id_pembeli', 'id_pembeli');
    }

    public function keranjang(): HasMany
    {
        return $this->hasMany(Keranjang::class, 'id_pembeli', 'id_pembeli');
    }

    public function conversation(): HasMany
    {
        return $this->hasMany(Conversation::class, 'pembeli_id', 'id_pembeli');
    }

    public function pembeliConversationsAsPembeli1(): HasMany
    {
        return $this->hasMany(PembeliConversation::class, 'pembeli1_id', 'id_pembeli');
    }

    public function pembeliConversationsAsPembeli2(): HasMany
    {
        return $this->hasMany(PembeliConversation::class, 'pembeli2_id', 'id_pembeli');
    }

    public function getAllPembeliConversations()
    {
        return \App\Models\PembeliConversation::where('pembeli1_id', $this->id_pembeli)
            ->orWhere('pembeli2_id', $this->id_pembeli)
            ->get();
    }
}
