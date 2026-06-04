<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('url');
            $table->string('ikon')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->boolean('buka_tab_baru')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
};
