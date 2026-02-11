<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Monitoring Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-gray-500">Total User</div>
                    <div class="text-2xl font-bold">{{ \App\Models\User::count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500">Log Aktivitas</div>
                    <div class="text-2xl font-bold">{{ \App\Models\ActivityLog::count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                   <div>
                       <div class="text-gray-500">Status Sistem</div>
                       <div class="text-green-600 font-bold">Online</div>
                   </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Log Aktivitas Terbaru</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Waktu</th>
                                    <th class="px-6 py-3">User</th>
                                    <th class="px-6 py-3">Aksi</th>
                                    <th class="px-6 py-3">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ $log->created_at->format('d M Y, H:i') }}
                                        <br><span class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $log->user->name ?? 'User Terhapus' }}
                                        <br>
                                        <span class="text-xs px-2 py-0.5 rounded {{ ($log->user->role ?? '') == 'admin' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $log->user->role ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 font-semibold leading-tight rounded-full 
                                            {{ $log->action == 'Login' ? 'text-green-700 bg-green-100' : 
                                              ($log->action == 'Delete User' ? 'text-red-700 bg-red-100' : 
                                              ($log->action == 'Logout' ? 'text-gray-700 bg-gray-100' : 'text-blue-700 bg-blue-100')) }}">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $log->description }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">Belum ada aktivitas tercatat.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>