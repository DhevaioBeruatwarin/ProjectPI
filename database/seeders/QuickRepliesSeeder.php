<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuickReply;

class QuickRepliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = QuickReply::getDefaultPembeliReplies();

        foreach ($defaults as $item) {
            QuickReply::create([
                'user_type' => 'pembeli',
                'user_id' => null,
                'title' => $item['title'] ?? substr($item['message'], 0, 50),
                'message' => $item['message'],
                'category' => $item['category'] ?? null,
                'is_active' => true,
            ]);
        }
    }
}
