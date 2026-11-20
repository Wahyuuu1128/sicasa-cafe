<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Menambahkan kolom foto setelah kolom harga
            $table->string('foto')->nullable()->after('harga'); 
        });
    }

    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Untuk menghapus kolom foto jika di-rollback
            $table->dropColumn('foto');
        });
    }
};
