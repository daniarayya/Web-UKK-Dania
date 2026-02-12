<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('input_aspirasis', function (Blueprint $table) {
            $table->id('id_input_aspirasi');
            $table->char('nisn', 10);
            $table->unsignedBigInteger('id_kategori');
            $table->string('lokasi');
            $table->text('keterangan');
            $table->string('foto')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('nisn')
                  ->references('nisn')
                  ->on('siswas')
                  ->cascadeOnDelete();

            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('kategoris')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('input_aspirasis');
    }
};
