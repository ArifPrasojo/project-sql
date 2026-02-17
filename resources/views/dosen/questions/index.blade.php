<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Bank Soal: {{ $material->title }}
            </h2>
            <a href="{{ route('dosen.class.show', $material->classroom_id) }}" class="text-gray-500 hover:underline text-sm">&larr; Kembali ke Modul</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow sticky top-6" x-data="questionForm()">
                        <h3 class="font-bold text-lg mb-4 border-b pb-2">Buat Soal SQL Baru</h3>
                        
                        <form action="{{ route('dosen.questions.store', $material->id) }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tipe Soal</label>
                                <select name="type" x-model="type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="essay">Essay / Query SQL (Standard)</option>
                                    <option value="fill_in_blank">Fill in the Blank (Isian)</option>
                                    <option value="drag_and_drop">Drag & Drop (Susun Query)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Instruksi / Pertanyaan</label>
                                <textarea name="question_text" rows="3" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Buatlah query untuk membuat tabel mahasiswa..." required></textarea>
                                
                                <p x-show="type === 'fill_in_blank'" class="text-xs text-orange-600 mt-1">
                                    *Gunakan simbol <b>[?]</b> di dalam Kunci Jawaban sebagai bagian yang kosong.
                                </p>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-1">
                                    <span x-text="type === 'fill_in_blank' ? 'Template Query (Gunakan [?] untuk bagian kosong)' : 'Kunci Jawaban (Query Benar)'"></span>
                                </label>
                                <textarea id="sql_editor" name="correct_answer_key"></textarea>
                                <p class="text-xs text-gray-500 mt-1">Editor ini mendukung Syntax Highlighting SQL.</p>
                            </div>

                            <div x-show="type === 'drag_and_drop'" class="mb-4 bg-gray-50 p-3 rounded border">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Potongan Query (Opsi)</label>
                                <template x-for="(option, index) in options" :key="index">
                                    <div class="flex gap-2 mb-2">
                                        <input type="text" name="options[]" x-model="options[index]" class="w-full border-gray-300 rounded-md text-sm" placeholder="Potongan (misal: CREATE)">
                                        <button type="button" @click="removeOption(index)" class="text-red-500 font-bold">&times;</button>
                                    </div>
                                </template>
                                <button type="button" @click="addOption()" class="text-sm text-indigo-600 font-bold hover:underline">+ Tambah Potongan</button>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Bobot Nilai</label>
                                <input type="number" name="points" value="10" class="w-full border-gray-300 rounded-md">
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md font-bold hover:bg-indigo-700 transition">
                                Simpan Soal
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-4">
                    <h3 class="font-bold text-lg text-gray-700 mb-4">Daftar Soal ({{ $questions->count() }})</h3>
                    
                    @forelse($questions as $index => $q)
                        <div class="bg-white p-5 rounded-lg shadow border-l-4 
                            {{ $q->type == 'essay' ? 'border-blue-500' : ($q->type == 'fill_in_blank' ? 'border-orange-500' : 'border-purple-500') }}">
                            
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="font-bold text-gray-400 text-lg mr-2">#{{ $index + 1 }}</span>
                                    <span class="text-xs uppercase font-bold px-2 py-1 rounded text-white
                                        {{ $q->type == 'essay' ? 'bg-blue-500' : ($q->type == 'fill_in_blank' ? 'bg-orange-500' : 'bg-purple-500') }}">
                                        {{ str_replace('_', ' ', $q->type) }}
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('dosen.questions.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Hapus soal ini?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 text-sm font-bold">Hapus</button>
                                    </form>
                                </div>
                            </div>

                            <p class="text-gray-800 font-medium mb-3">{{ $q->question_text }}</p>
                            
                            <div class="bg-gray-800 text-white p-3 rounded text-sm font-mono overflow-x-auto">
                                {{ $q->correct_answer_key }}
                            </div>

                            @if($q->type == 'drag_and_drop' && $q->options)
                                <div class="mt-3">
                                    <p class="text-xs font-bold text-gray-500 mb-1">Opsi Tersedia:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($q->options as $opt)
                                            <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs border border-gray-300">{{ $opt }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mt-2 text-right text-xs text-gray-500">
                                Bobot: {{ $q->points }} Poin
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-8 text-center rounded shadow border-dashed border-2 border-gray-300">
                            <p class="text-gray-500">Belum ada soal. Silakan buat soal baru di formulir sebelah kiri.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('questionForm', () => ({
                type: 'essay',
                options: [''], // Array untuk opsi drag & drop
                
                init() {
                    // Inisialisasi CodeMirror pada textarea ID sql_editor
                    var editor = CodeMirror.fromTextArea(document.getElementById("sql_editor"), {
                        mode: "text/x-sql",
                        theme: "dracula",
                        lineNumbers: true,
                        matchBrackets: true,
                        hintOptions: { tables: {
                            users: ["name", "score", "birthDate"],
                            products: ["name", "price", "category"]
                        }}
                    });

                    // Pastikan data CodeMirror masuk ke textarea saat form disubmit
                    editor.on('change', function(cm) {
                        document.getElementById("sql_editor").value = cm.getValue();
                    });
                },

                addOption() {
                    this.options.push('');
                },

                removeOption(index) {
                    this.options.splice(index, 1);
                }
            }))
        });
    </script>
</x-app-layout>