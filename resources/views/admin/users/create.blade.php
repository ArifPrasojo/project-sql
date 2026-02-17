<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                
                {{-- Form Header --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-900">Formulir Data Pengguna</h3>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi data di bawah ini untuk mendaftarkan pengguna baru.</p>
                </div>

                {{-- Form Body --}}
                <div class="p-6 sm:p-8">
                    <form action="{{ route('admin.users.store') }}" method="POST" x-data="{ role: 'mahasiswa' }">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="name" :value="__('Nama Lengkap')" class="mb-1" />
                                <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="Contoh: Budi Santoso" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="role" :value="__('Role / Jabatan')" class="mb-1" />
                                <select id="role" name="role" x-model="role" 
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                    <option value="mahasiswa">🎓 Mahasiswa</option>
                                    <option value="dosen">👨‍🏫 Dosen</option>
                                    <option value="admin">🛠️ Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 mb-6 transition-all duration-300">
                            <div x-show="role === 'mahasiswa'" x-transition>
                                <x-input-label for="nim" :value="__('Nomor Induk Mahasiswa (NIM)')" class="text-blue-800" />
                                <x-text-input id="nim" class="block mt-1 w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500" type="text" name="nim" :value="old('nim')" placeholder="Masukkan NIM..." />
                                <p class="text-xs text-blue-600 mt-1">*Wajib diisi untuk Mahasiswa</p>
                                <x-input-error :messages="$errors->get('nim')" class="mt-2" />
                            </div>

                            <div x-show="role === 'dosen' || role === 'admin'" style="display: none;" x-transition>
                                <x-input-label for="nik" :value="__('Nomor Induk Karyawan (NIK)')" class="text-blue-800" />
                                <x-text-input id="nik" class="block mt-1 w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500" type="text" name="nik" :value="old('nik')" placeholder="Masukkan NIK..." />
                                <p class="text-xs text-blue-600 mt-1">*Wajib diisi untuk Dosen atau Admin</p>
                                <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-6 mb-6">
                            <h4 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wide">Keamanan Akun</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="password" :value="__('Password')" class="mb-1" />
                                    <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="mb-1" />
                                    <x-text-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" required placeholder="••••••••" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Simpan User') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>