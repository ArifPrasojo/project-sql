<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Mahasiswa
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            
            // Jawaban siswa (Query SQL / Teks isian)
            $table->text('answer_text')->nullable();
            
            // Feedback dari sistem (misal: "Syntax Error near..." atau "Correct")
            $table->text('system_feedback')->nullable();
            
            // Nilai
            $table->decimal('score', 5, 2)->default(0);
            
            // Status pengerjaan
            $table->enum('status', ['submitted', 'graded'])->default('submitted');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};