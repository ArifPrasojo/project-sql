<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Kelas
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            // Relasi ke User (Dosen)
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            
            $table->string('name'); // Contoh: "Basis Data Lanjut"
            $table->string('code')->unique(); // Kode join kelas, misal: "BD-2024"
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tabel Pivot (Mahasiswa Join Kelas)
        Schema::create('class_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('joined_at')->useCurrent();
            
            // Mencegah mahasiswa masuk ke kelas yang sama 2 kali
            $table->unique(['classroom_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_enrollments');
        Schema::dropIfExists('classrooms');
    }
};
