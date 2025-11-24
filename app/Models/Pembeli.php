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
}
