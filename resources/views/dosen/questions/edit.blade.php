<x-app-layout>
    {{-- Push CSS CodeMirror ke Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Soal') }}
        </h2>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/dracula.min.css">
        <style>
            .CodeMirror {
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                height: 200px;
                font-size: 14px;
            }
        </style>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Header Form --}}
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-700">Form Edit Soal</h3>
                        <a href="{{ route('dosen.questions.index', $question->material_id) }}" 
                           class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali</a>
                    </div>

                    <form action="{{ route('dosen.questions.update', $question->id) }}" method="POST"
                          x-data="{ 
                              type: '{{ $question->type }}', 
                              options: {{ json_encode($question->options ?? []) }} 
                          }">
                        @csrf
                        @method('PUT')

                        {{-- 1. Pertanyaan --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pertanyaan</label>
                            <textarea name="question_text" rows="3" required
                                class="shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md w-full">{{ old('question_text', $question->question_text) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            {{-- 2. Tipe Soal --}}
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Soal</label>
                                <select name="type" x-model="type"
                                    class="shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md w-full">
                                    <option value="essay">Essay / Isian Singkat</option>
                                    <option value="fill_in_blank">Fill in Blank</option>
                                    <option value="drag_and_drop">Drag & Drop</option>
                                </select>
                            </div>

                            {{-- 3. Poin --}}
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Poin Bobot</label>
                                <input type="number" name="points" value="{{ old('points', $question->points) }}" required
                                    class="shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md w-full">
                            </div>
                        </div>

                        {{-- 4. Kunci Jawaban (CodeMirror) --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Kunci Jawaban (Expected Query/Text)
                            </label>
                            
                            <textarea id="correct_answer_key" name="correct_answer_key" required>{{ old('correct_answer_key', $question->correct_answer_key) }}</textarea>
                            
                            <p class="text-xs text-gray-500 mt-1">
                                Masukkan query SQL atau kunci jawaban yang benar.
                            </p>
                        </div>

                        {{-- 5. Opsi Tambahan (Dynamic Input untuk Drag & Drop) --}}
                        <div x-show="type === 'drag_and_drop'" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200" style="display: none;">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Opsi Pilihan (Drag Items)</label>
                            
                            <template x-for="(option, index) in options" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" :name="'options[]'" x-model="options[index]" placeholder="Isi opsi..."
                                        class="shadow-sm border-gray-300 rounded-md w-full text-sm">
                                    
                                    <button type="button" @click="options.splice(index, 1)"
                                        class="bg-red-100 text-red-600 px-3 rounded-md hover:bg-red-200 font-bold">
                                        &times;
                                    </button>
                                </div>
                            </template>

                            <button type="button" @click="options.push('')"
                                class="mt-2 text-sm text-indigo-600 font-semibold hover:underline flex items-center gap-1">
                                <span>+ Tambah Opsi</span>
                            </button>
                            
                            {{-- Fallback agar array tetap terkirim jika kosong --}}
                            <input type="hidden" name="options[]" value="" x-if="options.length === 0">
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 shadow transition">
                                Update Soal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script CodeMirror --}}
    @push('scripts') <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/sql/sql.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var textarea = document.getElementById('correct_answer_key');
            
            var editor = CodeMirror.fromTextArea(textarea, {
                mode: 'text/x-sql',
                theme: 'dracula',
                lineNumbers: true,
                indentWithTabs: true,
                smartIndent: true,
                matchBrackets: true,
                autofocus: false
            });

            // Penting: Sync perubahan CodeMirror ke Textarea asli
            editor.on('change', function (cm) {
                textarea.value = cm.getValue();
            });
        });
    </script>
    @endpush
    
    {{-- Fallback jika layout tidak pakai stack scripts, script ditaruh langsung disini --}}
    @if(!View::hasSection('scripts'))
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/sql/sql.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Cek agar tidak inisialisasi ganda
            if (document.querySelector('.CodeMirror')) return;

            var textarea = document.getElementById('correct_answer_key');
            var editor = CodeMirror.fromTextArea(textarea, {
                mode: 'text/x-sql',
                theme: 'dracula',
                lineNumbers: true,
                indentWithTabs: true,
                smartIndent: true,
                matchBrackets: true,
            });

            editor.on('change', function (cm) {
                textarea.value = cm.getValue();
            });
        });
    </script>
    @endif
</x-app-layout>