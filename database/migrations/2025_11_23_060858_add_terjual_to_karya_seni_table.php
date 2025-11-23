<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('karya_seni', function (Blueprint $table) {
            $table->integer('terjual')->default(0)->after('stok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('karya_seni', function (Blueprint $table) {
            $table->dropColumn('terjual');
        });
    }
};