<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('register_requests', function (Blueprint $table) {
            // Kolom baru untuk menyimpan array kantor_id yang dipilih
            $table->json('kantor_ids')->nullable()->after('kantor_id');
        });
    }

    public function down(): void
    {
        Schema::table('register_requests', function (Blueprint $table) {
            $table->dropColumn('kantor_ids');
        });
    }
};