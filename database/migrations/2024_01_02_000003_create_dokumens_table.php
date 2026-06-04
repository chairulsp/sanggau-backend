<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('dokumens')) {
            Schema::create('dokumens', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->string('kategori')->default('Umum');
                $table->string('file')->nullable();
                $table->string('file_url')->nullable();
                $table->text('deskripsi')->nullable();
                $table->string('tahun')->nullable();
                $table->integer('downloads')->default(0);
                $table->boolean('aktif')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('dokumens');
    }
};
