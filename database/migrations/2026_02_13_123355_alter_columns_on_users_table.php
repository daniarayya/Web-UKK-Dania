<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama', 100)->change();
            $table->string('username', 50)->change();
            $table->string('password', 255)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama', 255)->change();
            $table->string('username', 255)->unique()->change();
            $table->string('password', 255)->change();
        });
    }
};
