<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            
            // Identitas: NIM untuk Mahasiswa, NIK untuk Dosen/Admin
            // Dibuat nullable karena user hanya akan mengisi salah satu
            $table->string('nim')->nullable()->unique(); 
            $table->string('nik')->nullable()->unique();
            
            $table->string('password');
            
            // Role user sesuai proposal
            $table->enum('role', ['admin', 'dosen', 'mahasiswa'])->default('mahasiswa');
            
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabel untuk session (Standar Laravel 12)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
