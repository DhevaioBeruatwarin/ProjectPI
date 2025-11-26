<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PembeliConversation extends Model
{
    protected $table = 'pembeli_conversations';

    protected $fillable = [
        'pembeli1_id',
        'pembeli2_id',
    ];

    public function pembeli1(): BelongsTo
    {
        return $this->belongsTo(Pembeli::class, 'pembeli1_id', 'id_pembeli');
    }

    public function pembeli2(): BelongsTo
    {
        return $this->belongsTo(Pembeli::class, 'pembeli2_id', 'id_pembeli');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(PembeliChatMessage::class, 'pembeli_conversation_id');
    }

    /**
     * Get the other pembeli in the conversation
     */
    public function getOtherPembeli(int $currentPembeliId): ?Pembeli
    {
        if ($this->pembeli1_id === $currentPembeliId) {
            return $this->pembeli2;
        }
        if ($this->pembeli2_id === $currentPembeliId) {
            return $this->pembeli1;
        }
        return null;
    }

    /**
     * Check if pembeli is part of this conversation
     */
    public function hasPembeli(int $pembeliId): bool
    {
        return $this->pembeli1_id === $pembeliId || $this->pembeli2_id === $pembeliId;
    }
}

