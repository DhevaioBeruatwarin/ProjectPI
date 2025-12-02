<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembeliChatMessage extends Model
{
    protected $table = 'pembeli_chat_messages';

    protected $fillable = [
        'pembeli_conversation_id',
        'sender_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(PembeliConversation::class, 'pembeli_conversation_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Pembeli::class, 'sender_id', 'id_pembeli');
    }
}

