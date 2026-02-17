<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Soal</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            
            <form action="{{ route('dosen.questions.update', $question->id) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="mb-4">
                    <label class="block font-bold mb-1">Pertanyaan</label>
                    <textarea name="question_text" rows="4" class="w-full border p-2 rounded" required>{{ $question->question_text }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block font-bold mb-1">Kunci Jawaban</label>
                    <textarea name="correct_answer_key" rows="2" class="w-full border p-2 rounded font-mono bg-gray-50" required>{{ $question->correct_answer_key }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block font-bold mb-1">Bobot Nilai</label>
                    <input type="number" name="points" value="{{ $question->points }}" class="w-full border p-2 rounded">
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('dosen.questions.index', $question->material_id) }}" class="px-4 py-2 border rounded text-gray-600">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded font-bold hover:bg-indigo-700">Simpan Perubahan</button>
                </div>
            </form>

        </div>
    </div></div>
</x-app-layout>