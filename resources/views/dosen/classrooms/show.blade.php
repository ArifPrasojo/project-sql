<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ $classroom->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ activeTab: 'materi' }" class="bg-white overflow-hidden shadow-lg sm:rounded-xl ring-1 ring-gray-100">
                
                {{-- Tab Navigation --}}
                <div class="flex border-b border-gray-200 bg-gray-50">
                    <button @click="activeTab = 'materi'" 
                        :class="activeTab === 'materi' ? 'border-b-2 border-indigo-600 text-indigo-700 bg-white' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'"
                        class="w-1/2 py-5 px-6 text-center font-bold text-lg transition duration-200 ease-in-out flex justify-center items-center gap-2">
                        Materi Pembelajaran
                    </button>
                    <button @click="activeTab = 'ujian'" 
                        :class="activeTab === 'ujian' ? 'border-b-2 border-indigo-600 text-indigo-700 bg-white' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'"
                        class="w-1/2 py-5 px-6 text-center font-bold text-lg transition duration-200 ease-in-out flex justify-center items-center gap-2">
                        Tugas & Ujian
                    </button>
                </div>

                <div class="p-6 md:p-8 bg-white min-h-[400px]">
                    
                    {{-- TAB 1: MATERI --}}
                    <div x-show="activeTab === 'materi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        
                        {{-- Form Upload Materi --}}
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-100 mb-8 shadow-sm">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                </div>
                                <h3 class="font-bold text-blue-900 text-lg">Upload Materi Baru</h3>
                            </div>
                            
                            <form action="{{ route('dosen.material.store', $classroom->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="category" value="learning"> 
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Judul Materi</label>
                                        <input type="text" name="title" placeholder="Contoh: Pengenalan SQL" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm w-full transition duration-150" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">File Dokumen</label>
                                        <input type="file" name="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition bg-white border border-gray-300 rounded-lg cursor-pointer">
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Deskripsi</label>
                                    <textarea name="content" rows="2" placeholder="Deskripsi singkat materi..." class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm w-full transition duration-150"></textarea>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-blue-700 shadow-md hover:shadow-lg transition duration-150 flex items-center gap-2">
                                        <span>Simpan Materi</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- List Materi --}}
                        <div class="space-y-4">
                            @forelse($classroom->materials->where('category', 'learning') as $modul)
                                <div class="group flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-gray-200 p-5 rounded-xl hover:shadow-md hover:border-blue-200 transition duration-200">
                                    <div class="flex items-start gap-4 mb-3 sm:mb-0">
                                        <div class="bg-gray-100 p-3 rounded-lg text-2xl group-hover:bg-blue-50 transition">📄</div>
                                        <div>
                                            <h4 class="font-bold text-lg text-gray-800 group-hover:text-blue-700 transition">{{ $modul->title }}</h4>
                                            <p class="text-gray-500 text-sm mt-1 leading-relaxed">{{ $modul->content }}</p>
                                            @if($modul->file_path)
                                                <a href="{{ asset('storage/' . $modul->file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 text-xs font-bold mt-2 hover:underline bg-blue-50 px-2 py-1 rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                                    Download File
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 w-full sm:w-auto border-t sm:border-t-0 pt-3 sm:pt-0 mt-2 sm:mt-0 justify-end">
                                        <a href="{{ route('dosen.material.edit', $modul->id) }}" class="p-2 text-gray-400 hover:text-orange-500 hover:bg-orange-50 rounded-full transition" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form action="{{ route('dosen.material.destroy', $modul->id) }}" method="POST" onsubmit="return confirm('Hapus materi ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-full transition" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl">
                                    <div class="text-4xl mb-3">📂</div>
                                    <p class="text-gray-500 font-medium">Belum ada materi pembelajaran yang diunggah.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>


                    {{-- TAB 2: UJIAN --}}
                    <div x-show="activeTab === 'ujian'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        
                        {{-- Form Buat Ujian --}}
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-xl border border-yellow-100 mb-8 shadow-sm">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="bg-yellow-100 p-2 rounded-lg text-yellow-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                </div>
                                <h3 class="font-bold text-yellow-900 text-lg">Buat Ujian Baru (Pre/Post Test)</h3>
                            </div>

                            <form action="{{ route('dosen.material.store', $classroom->id) }}" method="POST">
                                @csrf
                                <div class="flex flex-col md:flex-row gap-4 items-end">
                                    <div class="flex-grow w-full">
                                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Judul Ujian</label>
                                        <input type="text" name="title" placeholder="Contoh: Pre-Test Modul 1" class="border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-lg shadow-sm w-full h-11 transition duration-150" required>
                                    </div>
                                    <div class="w-full md:w-1/4">
                                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Tipe</label>
                                        <select name="category" class="border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-lg shadow-sm w-full bg-white h-11 transition duration-150 cursor-pointer">
                                            <option value="pre_test">Pre-Test</option>
                                            <option value="post_test">Post-Test</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="w-full md:w-auto bg-yellow-600 text-white px-6 py-2 rounded-lg font-bold text-sm hover:bg-yellow-700 shadow-md hover:shadow-lg transition duration-150 h-11 flex items-center justify-center gap-2">
                                        <span class="whitespace-nowrap">Buat Ujian</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- List Ujian --}}
                        <div class="grid grid-cols-1 gap-5">
                            @forelse($classroom->materials->whereIn('category', ['pre_test', 'post_test']) as $exam)
                                <div class="relative bg-white border border-gray-200 p-6 rounded-xl shadow-sm hover:shadow-md transition duration-200 overflow-hidden">
                                    
                                    {{-- Color Indicator Strip --}}
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $exam->category == 'pre_test' ? 'bg-yellow-400' : 'bg-red-500' }}"></div>
                                    
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center pl-3">
                                        <div class="mb-4 md:mb-0">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h4 class="font-bold text-lg text-gray-800">{{ $exam->title }}</h4>
                                                <span class="text-[10px] uppercase px-2 py-1 rounded-full text-white font-bold tracking-wider {{ $exam->category == 'pre_test' ? 'bg-yellow-400' : 'bg-red-500' }}">
                                                    {{ $exam->category == 'pre_test' ? 'PRE-TEST' : 'POST-TEST' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center text-gray-500 text-sm gap-4">
                                                <span class="flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    {{ $exam->questions->count() }} Soal
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-3 self-end md:self-center">
                                            <a href="{{ route('dosen.questions.index', $exam->id) }}" class="bg-indigo-50 text-indigo-700 border border-indigo-200 px-4 py-2 rounded-lg shadow-sm hover:bg-indigo-600 hover:text-white font-bold text-sm transition duration-150 flex items-center gap-2 group">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                Kelola Soal
                                            </a>
                                            
                                            <div class="h-8 w-px bg-gray-300 mx-1"></div>

                                            <a href="{{ route('dosen.material.edit', $exam->id) }}" class="text-gray-400 hover:text-indigo-600 transition" title="Edit Judul">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </a>
                                            <form action="{{ route('dosen.material.destroy', $exam->id) }}" method="POST" onsubmit="return confirm('Hapus ujian ini beserta seluruh soalnya?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition pt-1" title="Hapus Ujian">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl">
                                    <div class="text-4xl mb-3">📝</div>
                                    <p class="text-gray-500 font-medium">Belum ada ujian yang dibuat.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>