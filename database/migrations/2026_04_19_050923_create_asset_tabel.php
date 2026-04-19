<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->string('kategori');
            $table->foreignId('kantor_id')->constrained('kantors')->cascadeOnDelete();
            $table->string('ruangan')->nullable();
            $table->enum('kondisi', ['Baik', 'Dalam Perbaikan', 'Rusak'])->default('Baik');
            $table->decimal('nilai', 15, 2)->default(0);
            $table->date('tanggal_pengadaan')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('merek')->nullable();
            $table->string('model')->nullable();
            $table->integer('garansi_bulan')->default(0);
            $table->date('garansi_habis')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};