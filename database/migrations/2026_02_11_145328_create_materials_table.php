<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            
            $table->string('title');
            
            $table->enum('category', ['learning', 'pre_test', 'post_test'])
                  ->default('learning');

            $table->text('content')->nullable(); // Deskripsi materi
            $table->string('file_path')->nullable(); // Opsi upload modul PDF
            $table->integer('sequence')->default(1); // Urutan materi dalam kelas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
