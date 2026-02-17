<?php
namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function joinClass(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:classrooms,code',
        ]);

        $classroom = Classroom::where('code', $request->code)->first();

        // Cek apakah sudah join (cek tabel pivot class_enrollments)
        $isJoined = DB::table('class_enrollments')
            ->where('classroom_id', $classroom->id)
            ->where('student_id', Auth::id())
            ->exists();

        if ($isJoined) {
            return back()->with('error', 'Anda sudah bergabung di kelas ini.');
        }

        // Masukkan ke tabel pivot
        DB::table('class_enrollments')->insert([
            'classroom_id' => $classroom->id,
            'student_id' => Auth::id(),
            'joined_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Berhasil bergabung ke kelas ' . $classroom->name);
    }
}