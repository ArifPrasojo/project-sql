<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User</h2></x-slot>

    <div class="py-12"><div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" x-data="{ role: '{{ $user->role }}' }">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <x-input-label for="name" :value="__('Nama Lengkap')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required />
                </div>

                <div class="mb-4">
                    <x-input-label for="role" :value="__('Role / Jabatan')" />
                    <select id="role" name="role" x-model="role" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="dosen">Dosen</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="mb-4" x-show="role === 'mahasiswa'">
                    <x-input-label for="nim" :value="__('NIM')" />
                    <x-text-input id="nim" class="block mt-1 w-full" type="text" name="nim" :value="old('nim', $user->nim)" />
                </div>

                <div class="mb-4" x-show="role === 'dosen' || role === 'admin'">
                    <x-input-label for="nik" :value="__('NIK')" />
                    <x-text-input id="nik" class="block mt-1 w-full" type="text" name="nik" :value="old('nik', $user->nik)" />
                </div>

                <hr class="my-4 border-gray-200">
                <p class="text-sm text-red-500 mb-2">* Kosongkan password jika tidak ingin mengganti.</p>

                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password Baru (Opsional)')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                </div>

                <div class="mb-4">
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('admin.users.index') }}" class="text-gray-600 underline mr-4">Batal</a>
                    <x-primary-button>{{ __('Update User') }}</x-primary-button>
                </div>
            </form>

        </div>
    </div></div>
</x-app-layout>