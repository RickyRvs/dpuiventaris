<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('register_requests', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('peran', ['admin', 'operator'])->default('operator');
            $table->foreignId('kantor_id')->nullable()->constrained('kantors')->nullOnDelete();
            $table->text('alasan');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->string('initials', 5)->default('US');
            $table->string('color1', 10)->default('#f97316');
            $table->string('color2', 10)->default('#c2410c');
            $table->string('catatan_admin')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('approved_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('register_requests');
    }
};