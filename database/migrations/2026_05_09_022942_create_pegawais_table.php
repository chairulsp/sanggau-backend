<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nip')->unique();
            $table->string('jabatan');
            $table->string('bidang')->nullable();
            $table->enum('status_pegawai', ['PNS', 'PPPK', 'Honorer']);
            $table->string('pangkat_golongan')->nullable();
            $table->year('tahun_bergabung')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('foto')->nullable();
            $table->integer('urutan')->default(1);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pegawai');
    }
}
