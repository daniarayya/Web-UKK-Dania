<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aspirasis', function (Blueprint $table) {
            // langsung drop kolom saja
            $table->dropColumn(['rejection_reason', 'rejected_at', 'rejected_by']);
        });
    }

    public function down(): void
    {
        Schema::table('aspirasis', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
        });
    }
};
