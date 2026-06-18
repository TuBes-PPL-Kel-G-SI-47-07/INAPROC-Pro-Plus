<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Immutable Audit Trail Log') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- NOTIFIKASI INTEGRITAS --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-xl shadow-sm mb-6" role="alert">
                    <p class="font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        Integritas Log Valid
                    </p>
                    <p class="text-sm mt-1">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-xl shadow-sm mb-6 animate-pulse" role="alert">
                    <p class="font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        Manipulasi Terdeteksi!
                    </p>
                    <p class="text-sm mt-1">{{ session('error') }}</p>
                </div>
            @endif

            <div class="flex justify-between items-center mb-4">
                <p class="text-gray-600 text-sm">Semua riwayat aksi (Create, Update, Delete) pada sistem dilindungi oleh <i>cryptographic hashing (SHA-256) berantai</i>. Hash terkini mengikat hash sebelumnya sehingga bersifat <i>tamper-proof</i> layaknya teknologi Blockchain.</p>
                
                <form action="{{ route('auditor.audit-trail.verify') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Verifikasi Integritas Log
                    </button>
                </form>
            </div>

            {{-- TABEL AUDIT TRAIL --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-4 py-3 font-medium border-b border-gray-100 text-center w-12">ID</th>
                                <th class="px-4 py-3 font-medium border-b border-gray-100">Waktu</th>
                                <th class="px-4 py-3 font-medium border-b border-gray-100">User ID</th>
                                <th class="px-4 py-3 font-medium border-b border-gray-100">Aktivitas (Action)</th>
                                <th class="px-4 py-3 font-medium border-b border-gray-100 w-1/3">Deskripsi</th>
                                <th class="px-4 py-3 font-medium border-b border-gray-100">Current Hash (SHA-256)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-4 py-3 text-center text-gray-500">{{ $log->id }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $log->user_id }} ({{ $log->user->name ?? 'System' }})</td>
                                    <td class="px-4 py-3">
                                        @if(str_contains(strtolower($log->action), 'created'))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">{{ $log->action }}</span>
                                        @elseif(str_contains(strtolower($log->action), 'updated'))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">{{ $log->action }}</span>
                                        @elseif(str_contains(strtolower($log->action), 'deleted'))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">{{ $log->action }}</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ $log->action }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 truncate max-w-xs" title="{{ $log->description }}">
                                        {{ Str::limit($log->description, 50) }}
                                    </td>
                                    <td class="px-4 py-3 text-xs font-mono text-gray-400 truncate max-w-xs" title="{{ $log->current_hash }}">
                                        {{ Str::limit($log->current_hash, 16) }}...
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada log aktivitas terekam.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination Links --}}
                @if($logs->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
