<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum pada kolom status
        DB::statement("ALTER TABLE aspirasis 
                      MODIFY COLUMN status 
                      ENUM('baru', 'menunggu', 'proses', 'selesai') 
                      DEFAULT 'baru'");
        
        // Update semua data yang statusnya 'menunggu' menjadi 'baru' 
        // (jika ingin data existing menjadi baru)
        DB::table('aspirasis')
            ->where('status', 'menunggu')
            ->update(['status' => 'baru']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update kembali data 'baru' menjadi 'menunggu' sebelum rollback
        DB::table('aspirasis')
            ->where('status', 'baru')
            ->update(['status' => 'menunggu']);
        
        // Kembalikan ke enum semula
        DB::statement("ALTER TABLE aspirasis 
                      MODIFY COLUMN status 
                      ENUM('menunggu', 'proses', 'selesai') 
                      DEFAULT 'menunggu'");
    }
};