<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            // Terhubung ke materials (bisa materi pre_test atau post_test)
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            
            // Jenis Soal sesuai Proposal
            $table->enum('type', ['fill_in_blank', 'drag_and_drop', 'essay']);
            
            // Level Kognitif Bloom Taxonomy (C1 - C6)
            $table->enum('cognitive_level', [
                'remembering', 'understanding', 'applying', 
                'analyzing', 'evaluating', 'creating'
            ]);

            $table->text('question_text');
            
            // Kolom JSON untuk menyimpan opsi jawaban (Khusus Drag & Drop)
            // Contoh isi: ["CREATE", "TABLE", "DROP", "ALTER"]
            $table->json('options')->nullable(); 
            
            // Kunci Jawaban (Query yang diharapkan)
            $table->text('correct_answer_key'); 
            
            $table->integer('points')->default(10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
