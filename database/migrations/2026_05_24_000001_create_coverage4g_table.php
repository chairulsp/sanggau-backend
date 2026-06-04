<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoverage4gTable extends Migration
{
    public function up()
    {
        Schema::create('coverage4g', function (Blueprint $table) {
            $table->id();
            $table->string('kecamatan', 100);
            $table->string('ibu_kota', 100)->nullable();
            $table->unsignedTinyInteger('persen')->default(0); // 0-100
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });

        // Seed data awal 15 kecamatan
        DB::table('coverage4g')->insert([
            ['kecamatan' => 'Sanggau',      'ibu_kota' => 'Sanggau',        'persen' => 92, 'urutan' => 1,  'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Kapuas',       'ibu_kota' => 'Sanggau',        'persen' => 85, 'urutan' => 2,  'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Tayan Hilir',  'ibu_kota' => 'Tayan',          'persen' => 78, 'urutan' => 3,  'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Tayan Hulu',   'ibu_kota' => 'Balai Sebut',    'persen' => 65, 'urutan' => 4,  'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Meliau',       'ibu_kota' => 'Meliau',         'persen' => 72, 'urutan' => 5,  'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Entikong',     'ibu_kota' => 'Entikong',       'persen' => 88, 'urutan' => 6,  'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Sekayam',      'ibu_kota' => 'Balai Karangan', 'persen' => 70, 'urutan' => 7,  'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Noyan',        'ibu_kota' => 'Noyan',          'persen' => 45, 'urutan' => 8,  'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Kembayan',     'ibu_kota' => 'Kembayan',       'persen' => 58, 'urutan' => 9,  'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Bonti',        'ibu_kota' => 'Bonti',          'persen' => 42, 'urutan' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Jangkang',     'ibu_kota' => 'Jangkang',       'persen' => 55, 'urutan' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Parindu',      'ibu_kota' => 'Pusat Damai',    'persen' => 68, 'urutan' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Toba',         'ibu_kota' => 'Toba',           'persen' => 38, 'urutan' => 13, 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Mukok',        'ibu_kota' => 'Mukok',          'persen' => 62, 'urutan' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan' => 'Beduai',       'ibu_kota' => 'Beduai',         'persen' => 35, 'urutan' => 15, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('coverage4g');
    }
}
