<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'pembeli_id',
        'seniman_id',
    ];

    public function pembeli(): BelongsTo
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id', 'id_pembeli');
    }

    public function seniman(): BelongsTo
    {
        return $this->belongsTo(Seniman::class, 'seniman_id', 'id_seniman');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }
}

