<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('ringkasan')->nullable();
            $table->longText('konten');
            $table->string('gambar')->nullable();
            $table->string('penulis')->nullable();
            $table->string('kategori')->default('umum');
            $table->string('tags')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('aktif')->default(true);
            $table->integer('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beritas');
    }
};
