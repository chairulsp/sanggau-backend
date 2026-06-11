<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEditorRoleAndNewsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add editor to users role enum
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'admin', 'editor', 'penulis') NOT NULL DEFAULT 'admin'");

        // Add columns to beritas table
        Schema::table('beritas', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->unsignedBigInteger('editor_id')->nullable()->after('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['editor_id']);
            $table->dropColumn(['user_id', 'editor_id']);
        });

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'admin', 'penulis') NOT NULL DEFAULT 'admin'");
    }
}
