<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama');
            $table->string('username')->unique();
            $table->string('password');
            $table->char('nisn', 10)->nullable();
            $table->enum('role', ['kepsek', 'admin', 'siswa']);
            $table->timestamps();
            $table->foreign('nisn')
                ->references('nisn')
                ->on('siswas')
                ->nullOnDelete(); 
        });
    }

   public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
