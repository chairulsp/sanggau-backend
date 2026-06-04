<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilDiskominfoSTable extends Migration
{
    public function up()
    {
        Schema::create('profil_diskominfoS', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dinas')->default('Dinas Komunikasi dan Informatika');
            $table->string('singkatan')->default('Diskominfo');
            $table->string('nama_kepala')->nullable();
            $table->string('nip_kepala')->nullable();
            $table->string('foto_kepala')->nullable();
            $table->string('nama_kabupaten')->default('Kabupaten Sanggau');
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('sejarah')->nullable();
            $table->text('tupoksi')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('jam_kerja')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profil_diskominfoS');
    }
}
