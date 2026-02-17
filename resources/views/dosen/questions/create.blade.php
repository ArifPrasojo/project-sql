<x-app-layout>
    <x-slot name="header"><h2>Input Soal: {{ $material->title }}</h2></x-slot>

    <div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            <form action="{{ route('dosen.question.store', $material->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block font-bold">Pertanyaan</label>
                    <textarea name="question_text" class="w-full border p-2 rounded h-24" required></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block font-bold">Tipe Soal</label>
                        <select name="type" class="w-full border p-2 rounded">
                            <option value="fill_in_blank">Isian Singkat (Fill in Blank)</option>
                            <option value="essay">Essay</option>
                            <option value="drag_and_drop">Drag & Drop</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold">Level Bloom</label>
                        <select name="cognitive_level" class="w-full border p-2 rounded">
                            <option value="remembering">C1 - Remembering (Mengingat)</option>
                            <option value="understanding">C2 - Understanding (Memahami)</option>
                            <option value="applying">C3 - Applying (Menerapkan)</option>
                            <option value="analyzing">C4 - Analyzing (Menganalisis)</option>
                            <option value="evaluating">C5 - Evaluating (Mengevaluasi)</option>
                            <option value="creating">C6 - Creating (Mencipta)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block font-bold">Kunci Jawaban (Query Benar)</label>
                    <input type="text" name="correct_answer_key" class="w-full border p-2 rounded" required placeholder="Contoh: SELECT * FROM users">
                </div>

                <div class="mb-4">
                    <label class="block font-bold">Opsi Drag & Drop (Pisahkan dengan koma)</label>
                    <input type="text" name="options" class="w-full border p-2 rounded" placeholder="Contoh: SELECT, FROM, WHERE, users">
                    <p class="text-xs text-gray-500">*Hanya diisi jika tipe soal Drag & Drop</p>
                </div>

                <div class="mb-4">
                    <label class="block font-bold">Poin Soal</label>
                    <input type="number" name="points" value="10" class="w-full border p-2 rounded">
                </div>

                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded">Simpan Soal</button>
            </form>
        </div>
    </div></div>
</x-app-layout>