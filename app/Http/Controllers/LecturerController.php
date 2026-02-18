<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Material;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\StudentAnswer;

class LecturerController extends Controller
{
    // === 1. MANAJEMEN KELAS ===

    public function createClass()
    {
        return view('dosen.classrooms.create');
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Generate Kode Unik 6 Karakter (Huruf Besar & Angka)
        do {
            $code = strtoupper(Str::random(6));
        } while (Classroom::where('code', $code)->exists());

        Classroom::create([
            'teacher_id' => Auth::id(), // Menggunakan teacher_id sesuai migrasi
            'name' => $request->name,
            'description' => $request->description,
            'code' => $code,
        ]);

        return redirect()->route('dashboard')->with('success', "Kelas berhasil dibuat! Kode Join: $code");
    }

    public function showClass(Classroom $classroom)
    {
        // Pastikan dosen pemilik yang akses
        if ($classroom->teacher_id !== Auth::id()) abort(403);

        // Load materials dan hitung jumlah soal
        $classroom->load(['materials.questions', 'students']);
        return view('dosen.classrooms.show', compact('classroom'));
    }

    // === 2. MANAJEMEN MATERI (PRE-TEST / POST-TEST) ===
    public function storeMaterial(Request $request, Classroom $classroom)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:learning,pre_test,post_test', // Sesuai enum migrasi
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials', 'public');
        }

        Material::create([
            'classroom_id' => $classroom->id,
            'title' => $request->input('title'),
            'category' => $request->input('category'),
            'content' => $request->input('content'),
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Materi/Ujian berhasil ditambahkan.');
    }

    // FORM EDIT MATERI
    public function editMaterial(Material $material)
    {
        // Pastikan dosen pemilik kelas yang akses
        if ($material->classroom->teacher_id !== Auth::id()) abort(403);
        
        return view('dosen.materials.edit', compact('material'));
    }

    // UPDATE MATERI
    public function updateMaterial(Request $request, Material $material)
    {
        if ($material->classroom->teacher_id !== Auth::id()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:learning,pre_test,post_test',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $data = [
            'title' => $request->input('title'),
            'category' => $request->input('category'),
            'content' => $request->input('content'),
        ];

        // Cek jika ada file baru diupload
        if ($request->hasFile('file')) {
            // 1. Hapus file lama jika ada
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            // 2. Upload file baru
            $data['file_path'] = $request->file('file')->store('materials', 'public');
        }

        $material->update($data);

        return redirect()->route('dosen.class.show', $material->classroom_id)
                         ->with('success', 'Materi berhasil diperbarui.');
    }

    // HAPUS MATERI (Delete)
    public function destroyMaterial(Material $material)
    {
        if ($material->classroom->teacher_id !== Auth::id()) abort(403);

        // Hapus file fisik dari storage
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        // Hapus data dari database
        $material->delete();

        return back()->with('success', 'Materi berhasil dihapus.');
    }

    // === 3. BANK SOAL (INPUT SOAL) ===

    public function createQuestion(Material $material)
    {
        return view('dosen.questions.create', compact('material'));
    }

    // Halaman Kelola Soal (Menampilkan List & Form Tambah)
    public function manageQuestions(Material $material)
    {
        // Pastikan materi ini adalah jenis ujian (bukan materi belajar biasa)
        if ($material->category === 'learning') {
            return back()->with('error', 'Fitur ini hanya untuk Pre-Test atau Post-Test.');
        }

        // Ambil soal yang sudah ada
        $questions = $material->questions;
        
        return view('dosen.questions.index', compact('material', 'questions'));
    }

public function storeQuestion(Request $request, Material $material)
    {
        $request->validate([
            'question_text' => 'required',
            'type' => 'required|in:essay,fill_in_blank,drag_and_drop',
            'correct_answer_key' => 'required',
            'points' => 'required|integer|min:1',
            'options' => 'nullable|array',
        ]);

        $options = null;
        
        if ($request->type === 'drag_and_drop') {
            // Hapus input kosong dari array options
            $options = array_filter($request->options ?? [], function($value) {
                return !is_null($value) && $value !== '';
            });
            // Re-index array supaya rapi (0, 1, 2...)
            $options = array_values($options);
        }

        // 4. Simpan ke Database
        Question::create([
            'material_id' => $material->id,
            'question_text' => $request->question_text,
            'type' => $request->type,
            'correct_answer_key' => $request->correct_answer_key,
            'points' => $request->points,
            'options' => $options, 
            'cognitive_level' => 'applying', 
        ]);

        return back()->with('success', 'Soal berhasil disimpan!');
    }
    // Form Edit Soal
    public function editQuestion(Question $question)
    {
        // Pastikan dosen pemilik kelas yang mengakses (Security Check)
        if ($question->material->classroom->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('dosen.questions.edit', compact('question'));
    }
    // Update Soal
    public function updateQuestion(Request $request, Question $question)
    {
        // 1. Validasi Input
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:essay,fill_in_blank,drag_and_drop', // Sesuaikan enum di DB Anda
            'points' => 'required|integer|min:1',
            'correct_answer_key' => 'required|string',
            // Validasi options hanya jika tipe bukan essay
            'options' => 'nullable|array', 
            'options.*' => 'nullable|string',
        ]);

        // 2. Cek apakah user berhak
        if ($question->material->classroom->teacher_id !== Auth::id()) {
            abort(403);
        }

        // 3. Proses Opsi (Jika ada)
        // Jika tipe soal butuh opsi (seperti drag_and_drop), ambil dari input array
        $options = null;
        if ($request->type === 'drag_and_drop' && $request->has('options')) {
            // Filter array agar tidak ada nilai kosong
            $options = array_values(array_filter($request->options, fn($value) => !is_null($value) && $value !== ''));
        }

        // 4. Update Database
        $question->update([
            'question_text' => $request->question_text,
            'type' => $request->type,
            'points' => $request->points,
            'correct_answer_key' => $request->correct_answer_key,
            'options' => $options, // Laravel akan otomatis mengubah array ke JSON jika di model di-cast
        ]);

        // 5. Redirect kembali ke halaman daftar soal (Questions Index)
        return redirect()->route('dosen.questions.index', $question->material_id)
                        ->with('success', 'Soal berhasil diperbarui!');
    }

    // Hapus Soal
    public function destroyQuestion(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Soal dihapus.');
    }
    
    public function showMaterialResults(Material $material)
    {
        // 1. Validasi Kepemilikan (Security)
        if ($material->classroom->teacher_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        // 2. Ambil semua soal dalam materi ini
        $questions = $material->questions;

        // 3. Ambil semua jawaban siswa untuk soal-soal tersebut
        // Kita load relasi 'user' agar tahu nama mahasiswanya
        $answers = StudentAnswer::whereIn('question_id', $questions->pluck('id'))
                    ->with('user')
                    ->get();

        // 4. Kelompokkan jawaban berdasarkan Mahasiswa (User ID)
        $groupedResults = $answers->groupBy('user_id')->map(function ($studentAnswers) use ($questions) {
            $student = $studentAnswers->first()->user;
            
            // Hitung total skor untuk materi ini
            $totalScore = $studentAnswers->sum('score');
            
            // Ubah format jawaban agar index-nya adalah question_id
            // Ini memudahkan kita menaruh jawaban di tabel nanti
            $answersMap = $studentAnswers->keyBy('question_id');

            return [
                'student' => $student,
                'total_score' => $totalScore,
                'answers' => $answersMap
            ];
        });

        return view('dosen.materials.results', compact('material', 'questions', 'groupedResults'));
    }

    // 1. Tampilkan Form Edit
    public function editClass(Classroom $classroom)
    {
        // Security: Pastikan hanya pemilik kelas yang bisa edit
        if ($classroom->teacher_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit kelas ini.');
        }

        return view('dosen.classrooms.edit', compact('classroom'));
    }

    // 2. Proses Update Data
    public function updateClass(Request $request, Classroom $classroom)
    {
        // Security Check
        if ($classroom->teacher_id !== Auth::id()) {
            abort(403);
        }

        // Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean' // Opsional: jika ingin menonaktifkan kelas
        ]);

        // Update Database
        $classroom->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('dosen.class.show', $classroom->id)
                        ->with('success', 'Informasi kelas berhasil diperbarui!');
    }
}