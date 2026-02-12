<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aspirasis', function (Blueprint $table) {
            $table->id('id_aspirasi');
            $table->enum('status',['menunggu','proses','selesai'])->default('menunggu');
            $table->unsignedBigInteger('id_kategori')->nullable();
            $table->unsignedBigInteger('id_input_aspirasi');
            $table->timestamps();

            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('kategoris')
                  ->nullOnDelete();

            $table->foreign('id_input_aspirasi')
                  ->references('id_input_aspirasi')
                  ->on('input_aspirasis')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspirasis');
    }
};
