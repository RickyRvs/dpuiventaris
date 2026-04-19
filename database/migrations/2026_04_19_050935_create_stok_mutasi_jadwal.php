<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Stok ─────────────────────────────────────────────────
        Schema::create('stoks', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->string('satuan', 30);
            $table->integer('stok')->default(0);
            $table->integer('min_stok')->default(0);
            $table->string('kategori');
            $table->foreignId('kantor_id')->constrained('kantors')->cascadeOnDelete();
            $table->timestamps();
        });

        // ── Mutasi ────────────────────────────────────────────────
        Schema::create('mutasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->foreignId('aset_id')->constrained('asets')->cascadeOnDelete();
            $table->foreignId('kantor_asal_id')->constrained('kantors');
            $table->foreignId('kantor_tujuan_id')->constrained('kantors');
            $table->foreignId('pengaju_id')->constrained('users');
            $table->text('alasan')->nullable();
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->timestamps();
        });

        // ── Jadwal Pemeliharaan ───────────────────────────────────
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->foreignId('aset_id')->constrained('asets')->cascadeOnDelete();
            $table->foreignId('kantor_id')->constrained('kantors');
            $table->string('jenis');
            $table->string('teknisi');
            $table->date('tanggal');
            $table->string('waktu', 30)->nullable();   // "09:00 - 12:00"
            $table->text('catatan')->nullable();
            $table->enum('status', ['Terjadwal', 'Dalam Proses', 'Selesai', 'Terlewat'])
                  ->default('Terjadwal');
            $table->timestamps();
        });

        // ── Audit Log ─────────────────────────────────────────────
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->text('aksi');
            $table->string('modul', 50);
            $table->string('icon', 50)->default('edit');
            $table->string('bg', 20)->default('#fff7ed');
            $table->string('ic', 20)->default('#f97316');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('jadwals');
        Schema::dropIfExists('mutasis');
        Schema::dropIfExists('stoks');
    }
};