<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ppids')) {
            Schema::create('ppids', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->string('kategori')->default('Wajib Tersedia');
                $table->string('file')->nullable();
                $table->string('file_url')->nullable();
                $table->text('deskripsi')->nullable();
                $table->string('tahun')->nullable();
                $table->integer('urutan')->default(0);
                $table->boolean('aktif')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('ppids');
    }
};
