<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('struktur_organisasis')) {
            Schema::create('struktur_organisasis', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('jabatan');
                $table->string('nip')->nullable();
                $table->string('foto')->nullable();
                $table->string('email')->nullable();
                $table->string('telepon')->nullable();
                $table->integer('urutan')->default(0);
                $table->boolean('aktif')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('struktur_organisasis');
    }
};
