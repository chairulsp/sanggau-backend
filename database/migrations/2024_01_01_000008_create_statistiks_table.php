<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('statistiks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nilai');
            $table->string('satuan')->nullable();
            $table->string('ikon')->default('fa-chart-bar');
            $table->string('warna')->default('#1B5E20');
            $table->integer('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('statistiks');
    }
};
