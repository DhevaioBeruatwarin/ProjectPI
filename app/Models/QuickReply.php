<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuickReply extends Model
{
    protected $table = 'quick_replies';

    protected $fillable = [
        'user_type',
        'user_id',
        'title',
        'message',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get default quick replies for pembeli
     */
    public static function getDefaultPembeliReplies()
    {
        return [
            [
                'title' => 'Barang masih tersedia?',
                'message' => 'Halo, apakah karya seni ini masih tersedia untuk dijual?',
                'category' => 'availability',
                'emoji' => '📦'
            ],
            [
                'title' => 'Harga dan pengiriman',
                'message' => 'Berapa harga terakhirnya? Apakah Anda melayani pengiriman?',
                'category' => 'pricing',
                'emoji' => '💰'
            ],
            [
                'title' => 'Customization?',
                'message' => 'Apakah Anda bisa membuat karya dengan desain atau tema custom?',
                'category' => 'customization',
                'emoji' => '🎨'
            ],
            [
                'title' => 'Bahan dan teknik',
                'message' => 'Apa bahan dan teknik yang digunakan untuk membuat karya ini?',
                'category' => 'technical',
                'emoji' => '🛠️'
            ],
            [
                'title' => 'Sertifikat keaslian',
                'message' => 'Apakah produk ini dilengkapi dengan sertifikat keaslian?',
                'category' => 'certificate',
                'emoji' => '📜'
            ],
            [
                'title' => 'Garansi',
                'message' => 'Apa garansi atau jaminan yang Anda berikan?',
                'category' => 'warranty',
                'emoji' => '✅'
            ],
        ];
    }

    /**
     * Get active quick replies for user
     */
    public static function getActiveByUser(string $userType, int $userId)
    {
        return static::where('user_type', $userType)
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('category')
            ->get();
    }
}
