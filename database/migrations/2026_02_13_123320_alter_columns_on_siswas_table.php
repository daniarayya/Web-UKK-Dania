<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('nama', 100)->change();
            $table->enum('kelas', ['10', '11', '12'])->change();
            $table->enum('jurusan', ['RPL', 'FKK', 'BDP'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('nama', 255)->change();
            $table->string('kelas', 255)->change();
            $table->string('jurusan', 255)->change();
        });
    }
};
