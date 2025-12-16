<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('review_responses', function (Blueprint $table) {
            $table->id('id_response');
            $table->unsignedBigInteger('id_review');
            $table->unsignedBigInteger('id_seniman');
            $table->text('tanggapan');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('id_review')
                ->references('id_review')
                ->on('reviews')
                ->onDelete('cascade');

            $table->foreign('id_seniman')
                ->references('id_seniman')
                ->on('seniman')
                ->onDelete('cascade');

            // Index for performance
            $table->index('id_review');
            $table->index('id_seniman');
        });
    }

    public function down()
    {
        Schema::dropIfExists('review_responses');
    }
};