<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembeli_id');
            $table->unsignedBigInteger('seniman_id');
            $table->timestamps();

            $table->unique(['pembeli_id', 'seniman_id'], 'conversations_unique_pair');

            $table->foreign('pembeli_id')
                ->references('id_pembeli')
                ->on('pembeli')
                ->cascadeOnDelete();

            $table->foreign('seniman_id')
                ->references('id_seniman')
                ->on('seniman')
                ->cascadeOnDelete();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')
                ->constrained('conversations')
                ->cascadeOnDelete();
            $table->enum('sender_type', ['pembeli', 'seniman']);
            $table->unsignedBigInteger('sender_id');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['sender_type', 'sender_id'], 'chat_messages_sender_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('conversations');
    }
};

