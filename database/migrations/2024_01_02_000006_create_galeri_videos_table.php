<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('galeri_videos')) {
            Schema::create('galeri_videos', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->string('url_youtube');
                $table->string('thumbnail')->nullable();
                $table->text('deskripsi')->nullable();
                $table->date('tanggal')->nullable();
                $table->boolean('aktif')->default(true);
                $table->integer('urutan')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('galeri_videos');
    }
};
