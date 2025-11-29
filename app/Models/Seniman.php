<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Seniman extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public $timestamps = false;
    protected $table = 'seniman';
    protected $primaryKey = 'id_seniman';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'alamat',
        'no_hp',
        'foto',
        'bio',
        'bidang',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Method untuk custom primary key
    public function getAuthIdentifierName()
    {
        return 'id_seniman';
    }

    public function getAuthIdentifier()
    {
        return $this->id_seniman;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    // Relasi: seorang seniman bisa punya banyak karya
    public function karya()
    {
        return $this->hasMany(KaryaSeni::class, 'id_seniman', 'id_seniman');

    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'seniman_id', 'id_seniman');
    }
}