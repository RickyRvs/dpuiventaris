<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_kantors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('kantor_id')
                  ->constrained('kantors')
                  ->cascadeOnDelete();
            $table->timestamps();

            // Pastikan tidak ada duplikat user + kantor
            $table->unique(['user_id', 'kantor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_kantors');
    }
};