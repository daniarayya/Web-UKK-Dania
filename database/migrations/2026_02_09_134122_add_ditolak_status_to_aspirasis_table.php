<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan enum 'ditolak' pada kolom status
        DB::statement("ALTER TABLE aspirasis 
                      MODIFY COLUMN status 
                      ENUM('baru', 'menunggu', 'proses', 'selesai', 'ditolak') 
                      DEFAULT 'baru'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ubah data yang sudah 'ditolak' menjadi 'baru' sebelum rollback
        DB::table('aspirasis')
            ->where('status', 'ditolak')
            ->update(['status' => 'baru']);

        // Kembalikan enum tanpa 'ditolak'
        DB::statement("ALTER TABLE aspirasis 
                      MODIFY COLUMN status 
                      ENUM('baru', 'menunggu', 'proses', 'selesai') 
                      DEFAULT 'baru'");
    }
};
