<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Management User</h2></x-slot>

    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-bold">Daftar Pengguna</h3>
                <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah User</a>
            </div>

            <table class="min-w-full border-collapse border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2 text-left">Nama</th>
                        <th class="border p-2 text-left">Role</th>
                        <th class="border p-2 text-left">Identitas (NIM/NIK)</th>
                        <th class="border p-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-2">{{ $u->name }}</td>
                        <td class="border p-2 capitalize">
                            <span class="px-2 py-1 text-xs rounded text-white 
                                {{ $u->role == 'admin' ? 'bg-red-500' : ($u->role == 'dosen' ? 'bg-green-500' : 'bg-gray-500') }}">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="border p-2">{{ $u->nim ?? $u->nik ?? '-' }}</td>
                        <td class="border p-2 text-center space-x-2">
                            <a href="{{ route('admin.users.edit', $u->id) }}" class="text-yellow-600 hover:underline">Edit</a>
                            
                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $users->links() }}</div>
        </div>
    </div></div>
</x-app-layout>