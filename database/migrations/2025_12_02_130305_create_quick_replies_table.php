<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quick_replies', function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->nullable()->comment('pembeli|seniman|system');
            $table->unsignedBigInteger('user_id')->nullable()->comment('id pengguna yang memiliki quick replies (nullable = default/system)');
            $table->string('title');
            $table->text('message');
            $table->string('category')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_replies');
    }
};
