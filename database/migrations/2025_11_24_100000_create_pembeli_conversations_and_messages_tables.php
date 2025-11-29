<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembeli_conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembeli1_id');
            $table->unsignedBigInteger('pembeli2_id');
            $table->timestamps();

            // Ensure unique conversation between two pembeli (order doesn't matter)
            $table->unique(['pembeli1_id', 'pembeli2_id'], 'pembeli_conversations_unique_pair');

            $table->foreign('pembeli1_id')
                ->references('id_pembeli')
                ->on('pembeli')
                ->cascadeOnDelete();

            $table->foreign('pembeli2_id')
                ->references('id_pembeli')
                ->on('pembeli')
                ->cascadeOnDelete();
        });

        Schema::create('pembeli_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembeli_conversation_id')
                ->constrained('pembeli_conversations')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('sender_id');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('sender_id')
                ->references('id_pembeli')
                ->on('pembeli')
                ->cascadeOnDelete();

            $table->index(['pembeli_conversation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembeli_chat_messages');
        Schema::dropIfExists('pembeli_conversations');
    }
};

