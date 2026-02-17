<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Materi: {{ $material->title }}</h2>
    </x-slot>

    <div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            
            <form action="{{ route('dosen.material.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <div class="mb-4">
                    <label class="block font-bold mb-1">Judul Materi</label>
                    <input type="text" name="title" value="{{ $material->title }}" class="w-full border p-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-bold mb-1">Kategori</label>
                    <select name="category" class="w-full border p-2 rounded">
                        <option value="learning" {{ $material->category == 'learning' ? 'selected' : '' }}>Materi Belajar</option>
                        <option value="pre_test" {{ $material->category == 'pre_test' ? 'selected' : '' }}>Pre-Test</option>
                        <option value="post_test" {{ $material->category == 'post_test' ? 'selected' : '' }}>Post-Test</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-bold mb-1">Konten / Deskripsi</label>
                    <textarea name="content" rows="5" class="w-full border p-2 rounded">{{ $material->content }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block font-bold mb-1">Ganti File (Opsional)</label>
                    @if($material->file_path)
                        <p class="text-sm text-green-600 mb-2">✅ File saat ini: <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="underline">Lihat File</a></p>
                    @endif
                    <input type="file" name="file" class="w-full border p-2 rounded bg-gray-50">
                    <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah file.</p>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('dosen.class.show', $material->classroom_id) }}" class="px-4 py-2 text-gray-600 border rounded hover:bg-gray-50">Batal</a>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 font-bold">Simpan Perubahan</button>
                </div>
            </form>

        </div>
    </div></div>
</x-app-layout>