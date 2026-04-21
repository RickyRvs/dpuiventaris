<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id();

            // Nomor dokumen, digenerate otomatis: BA-YYYYMM-XXX
            $table->string('nomor', 30)->unique();

            // Relasi ke aset (bisa null jika berita acara gabungan)
            $table->foreignId('aset_id')->nullable()->constrained('asets')->nullOnDelete();

            // Relasi ke kantor
            $table->foreignId('kantor_id')->nullable()->constrained('kantors')->nullOnDelete();

            // Pihak Pertama - Pimpinan/Direksi PT
            $table->string('pihak_pertama_nama', 150)->default('');
            $table->string('pihak_pertama_jabatan', 150)->default('Direktur Utama');

            // Pihak Kedua - Penerima / PIC Barang
            $table->string('pihak_kedua_nama', 150)->default('');
            $table->string('pihak_kedua_jabatan', 150)->default('');

            // Detail serah terima
            $table->date('tanggal_serah_terima');
            $table->text('keterangan')->nullable();

            // Aset snapshot (nama & kode saat dokumen dibuat)
            $table->string('aset_nama', 255)->nullable();
            $table->string('aset_kode', 50)->nullable();
            $table->string('aset_kategori', 100)->nullable();
            $table->string('aset_kondisi', 50)->nullable();
            $table->decimal('aset_nilai', 15, 2)->default(0);

            /*
             * Status alur dokumen:
             *   draft           → baru dibuat, template belum diunduh
             *   template_downloaded → template sudah diunduh, menunggu TTD + materai
             *   menunggu_upload → mengingatkan user untuk upload dokumen TTD
             *   selesai         → dokumen sudah ditandatangani & diupload kembali
             */
            $table->enum('status', ['draft', 'template_downloaded', 'menunggu_upload', 'selesai'])
                  ->default('draft');

            // Path file dokumen TTD yang sudah diupload
            $table->string('dokumen_signed_path', 500)->nullable();
            $table->string('dokumen_signed_nama', 255)->nullable();
            $table->timestamp('uploaded_at')->nullable();

            // Siapa yang membuat berita acara
            $table->string('dibuat_oleh', 150)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_acaras');
    }
};