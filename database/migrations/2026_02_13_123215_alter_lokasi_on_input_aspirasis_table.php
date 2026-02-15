<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('input_aspirasis', function (Blueprint $table) {
            $table->string('lokasi', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('input_aspirasis', function (Blueprint $table) {
            $table->string('lokasi', 255)->change();
        });
    }
};
