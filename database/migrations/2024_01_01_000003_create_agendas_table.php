<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('gambar')->nullable();
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->string('penyelenggara')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agendas');
    }
};
