<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kantors', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();       // pku, jkt, sby, bks
            $table->string('nama');                      // Nama lengkap kantor
            $table->string('short_name', 60);            // Nama pendek
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kantors');
    }
};