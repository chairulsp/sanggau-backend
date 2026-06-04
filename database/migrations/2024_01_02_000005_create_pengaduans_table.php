<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pengaduans')) {
            Schema::create('pengaduans', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('email');
                $table->string('telepon')->nullable();
                $table->string('subjek');
                $table->text('pesan');
                $table->enum('status', ['baru', 'diproses', 'selesai'])->default('baru');
                $table->text('balasan')->nullable();
                $table->timestamp('dibalas_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pengaduans');
    }
};
