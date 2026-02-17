<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Edit Materi Pembelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                
                {{-- Header Form --}}
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Perbarui Konten</h3>
                        <p class="text-sm text-gray-500">Ubah detail materi atau lampiran file.</p>
                    </div>
                    {{-- Tombol Kembali Kecil --}}
                    <a href="{{ route('dosen.class.show', $material->classroom_id) }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Kelas
                    </a>
                </div>

                <div class="p-6 sm:p-8">
                    <form action="{{ route('dosen.material.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Section 1: Informasi Utama --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            {{-- Judul Materi --}}
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Materi</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $material->title) }}" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition" 
                                    placeholder="Contoh: Pengantar Algoritma Dasar" required>
                                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Kategori --}}
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <div class="relative">
                                    <select name="category" id="category" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 appearance-none cursor-pointer">
                                        <option value="learning" {{ $material->category == 'learning' ? 'selected' : '' }}>📚 Materi Belajar</option>
                                        <option value="pre_test" {{ $material->category == 'pre_test' ? 'selected' : '' }}>📝 Pre-Test</option>
                                        <option value="post_test" {{ $material->category == 'post_test' ? 'selected' : '' }}>🎓 Post-Test</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: Konten Deskripsi --}}
                        <div class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Isi Materi</label>
                            <textarea name="content" id="content" rows="6" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition"
                                placeholder="Tuliskan deskripsi lengkap materi di sini...">{{ old('content', $material->content) }}</textarea>
                            <p class="text-xs text-gray-400 mt-1 text-right">Mendukung format teks biasa.</p>
                        </div>

                        {{-- Section 3: File Attachment --}}
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 mb-8">
                            <label class="block text-sm font-bold text-blue-900 mb-3">Lampiran File</label>
                            
                            <div class="flex flex-col md:flex-row md:items-center gap-4">
                                {{-- Existing File Info --}}
                                @if($material->file_path)
                                    <div class="flex items-center p-3 bg-white border border-blue-200 rounded-lg shadow-sm min-w-[200px]">
                                        <div class="bg-blue-100 p-2 rounded text-blue-600 mr-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">File Saat Ini</p>
                                            <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline hover:text-blue-800">
                                                Lihat / Download
                                            </a>
                                        </div>
                                    </div>
                                    <div class="hidden md:block text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                    </div>
                                @endif

                                {{-- New File Input --}}
                                <div class="flex-1">
                                    <input type="file" name="file" 
                                        class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2.5 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-600 file:text-white
                                        hover:file:bg-blue-700
                                        cursor-pointer bg-white rounded-full border border-gray-200 shadow-sm
                                        transition">
                                    <p class="text-xs text-blue-800 mt-2 ml-2">*Upload file baru hanya jika ingin mengganti file lama.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('dosen.class.show', $material->classroom_id) }}" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 font-semibold text-xs uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ __('Simpan Perubahan') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>