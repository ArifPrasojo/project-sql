<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                
                {{-- Header Card --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Perbarui Informasi</h3>
                        <p class="text-sm text-gray-500 mt-1">Ubah data profil dan hak akses pengguna.</p>
                    </div>
                    <div class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                        {{ $user->role }}
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" x-data="{ role: '{{ $user->role }}' }">
                        @csrf
                        @method('PUT')

                        {{-- Section 1: Data Utama --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Nama Lengkap --}}
                            <div>
                                <x-input-label for="name" :value="__('Nama Lengkap')" class="mb-1" />
                                <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name', $user->name)" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            {{-- Role Selection --}}
                            <div>
                                <x-input-label for="role" :value="__('Role / Jabatan')" class="mb-1" />
                                <select id="role" name="role" x-model="role" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer">
                                    <option value="mahasiswa">🎓 Mahasiswa</option>
                                    <option value="dosen">👨‍🏫 Dosen</option>
                                    <option value="admin">🛠️ Admin</option>
                                </select>
                            </div>
                        </div>

                        {{-- Section 2: Identitas Dinamis (NIM/NIK) --}}
                        <div class="bg-indigo-50 p-5 rounded-xl border border-indigo-100 mb-8 transition-all duration-300">
                            <div x-show="role === 'mahasiswa'" x-transition>
                                <x-input-label for="nim" :value="__('Nomor Induk Mahasiswa (NIM)')" class="text-indigo-900 font-bold" />
                                <x-text-input id="nim" class="block mt-2 w-full border-indigo-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="nim" :value="old('nim', $user->nim)" placeholder="Masukkan NIM" />
                            </div>

                            <div x-show="role === 'dosen' || role === 'admin'" style="display: none;" x-transition>
                                <x-input-label for="nik" :value="__('Nomor Induk Karyawan (NIK)')" class="text-indigo-900 font-bold" />
                                <x-text-input id="nik" class="block mt-2 w-full border-indigo-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="nik" :value="old('nik', $user->nik)" placeholder="Masukkan NIK" />
                            </div>
                        </div>

                        {{-- Section 3: Ganti Password --}}
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex items-center gap-2 mb-4">
                                <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Ubah Password</h4>
                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded border border-yellow-200">Opsional</span>
                            </div>

                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-5">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            Biarkan kolom password kosong jika Anda tidak ingin mengubah password pengguna saat ini.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="password" :value="__('Password Baru')" class="mb-1" />
                                    <x-text-input id="password" class="block w-full" type="password" name="password" autocomplete="new-password" />
                                </div>
                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="mb-1" />
                                    <x-text-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" />
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-semibold text-xs uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                {{ __('Update Perubahan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>