<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('profil_kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kecamatan');
            $table->string('nama_kabupaten')->default('Kabupaten Sanggau');
            $table->string('nama_camat')->nullable();
            $table->string('nip_camat')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('sejarah')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('foto_camat')->nullable();
            $table->string('jumlah_desa')->nullable();
            $table->string('jumlah_penduduk')->nullable();
            $table->string('luas_wilayah')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profil_kecamatans');
    }
};
