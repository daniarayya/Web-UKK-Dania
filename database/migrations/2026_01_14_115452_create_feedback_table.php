<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id('id_feedback');
            $table->unsignedBigInteger('id_aspirasi');
            $table->unsignedBigInteger('id_user');
            $table->text('isi');

            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_aspirasi')
                  ->references('id_aspirasi')
                  ->on('aspirasis')
                  ->cascadeOnDelete();

            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('users')
                  ->cascadeOnDelete();      
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
