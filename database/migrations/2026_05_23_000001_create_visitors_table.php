<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorsTable extends Migration
{
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 64)->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('halaman', 500)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('device', 20)->default('desktop'); // desktop, mobile, tablet
            $table->string('browser', 50)->nullable();
            $table->string('os', 50)->nullable();
            $table->boolean('is_new')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitors');
    }
}
