<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Tambahkan kolom harga setelah kolom jumlah
            $table->integer('harga')->after('jumlah');
        });
    }

    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
    }
};
