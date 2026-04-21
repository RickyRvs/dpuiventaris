<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_acara_aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_acara_id')
                  ->constrained('berita_acaras')
                  ->onDelete('cascade');
            $table->foreignId('aset_id')
                  ->constrained('asets')
                  ->onDelete('cascade');

            // Snapshot data aset saat BA dibuat
            $table->string('aset_nama')->nullable();
            $table->string('aset_kode')->nullable();
            $table->string('aset_kategori')->nullable();
            $table->string('aset_kondisi')->nullable();
            $table->decimal('aset_nilai', 15, 2)->default(0);

            $table->timestamps();
        });

        // Kolom aset_id di berita_acaras jadi nullable
        // karena sekarang relasinya many-to-many
        Schema::table('berita_acaras', function (Blueprint $table) {
            $table->foreignId('aset_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_acara_aset');
    }
};