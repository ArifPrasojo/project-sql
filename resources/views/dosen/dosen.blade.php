<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Dosen') }}
            </h2>
            <a href="{{ route('dosen.class.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-indigo-700 transition shadow">
                + Buat Kelas Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Halo, {{ $user->name }}!</h3>
                        <p class="text-gray-600 text-sm">NIK: <span class="font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $user->nik ?? '-' }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-indigo-600">{{ count($classrooms ?? []) }}</p>
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Kelas Aktif</p>
                    </div>
                </div>
            </div>

            <h3 class="font-bold text-xl text-gray-800 mt-8 mb-4">Daftar Kelas Anda</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($classrooms as $class)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:shadow-lg transition duration-200 flex flex-col h-full">
                        <div class="p-6 flex-grow">
                            <h4 class="font-bold text-xl text-gray-800 mb-2 truncate" title="{{ $class->name }}">
                                {{ $class->name }}
                            </h4>
                            
                            <div class="mb-4">
                                <span class="text-xs text-gray-500">Kode Kelas:</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded text-lg font-mono font-bold select-all">
                                        {{ $class->code }}
                                    </span>
                                </div>
                            </div>

                            <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                {{ $class->description ?? 'Tidak ada deskripsi.' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 mt-auto">
                            <a href="{{ route('dosen.class.show', $class->id) }}" class="block w-full text-center bg-indigo-600 text-white py-2 rounded-md font-bold hover:bg-indigo-700 transition">
                                Kelola Kelas &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg shadow-sm p-12 text-center border-2 border-dashed border-gray-300">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada kelas</h3>
                        <p class="mt-1 text-gray-500">Mulai dengan membuat kelas pertama Anda.</p>
                        <div class="mt-6">
                            <a href="{{ route('dosen.class.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Buat Kelas Baru
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>