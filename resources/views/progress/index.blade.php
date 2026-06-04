<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-tighter flex items-center">
            <span class="mr-3 text-3xl">🏗️</span>
            {{ __('Monitoring Progres Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12 relative overflow-hidden min-h-screen bg-slate-50">
        <!-- Background decorative blur -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10 space-y-8">
            
            {{-- HEADER CARD --}}
            <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 rounded-[3rem] p-10 md:p-14 text-white shadow-2xl relative overflow-hidden group">
                <div class="relative z-10 max-w-2xl">
                    <span class="text-[10px] bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-4 py-2 rounded-full font-black uppercase tracking-[0.2em] mb-6 inline-block">Fase Pasca-Lelang & Pelaksanaan</span>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tighter leading-none mb-4 uppercase">
                        Real-time Visual Progress
                    </h1>
                    <p class="text-slate-300 text-sm md:text-base font-medium leading-relaxed">
                        Pantau status pengerjaan proyek secara berkala melalui dokumentasi visual. Dilengkapi verifikasi koordinat GPS dan ekstraksi timestamp asli untuk menjamin akuntabilitas pengerjaan di lapangan.
                    </p>
                </div>
                <div class="absolute -bottom-20 -right-20 h-80 w-80 bg-indigo-500/10 rounded-full blur-[100px] group-hover:bg-indigo-500/20 transition-all duration-750"></div>
            </div>

            {{-- MAIN PROJECT GRID --}}
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-[3rem] border border-gray-100 p-8 md:p-12">
                <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Daftar Kontrak & Proyek Aktif</h2>
                        <p class="text-gray-500 mt-1 text-sm font-medium">Log proyek yang telah memenangkan tender dan sedang dalam masa pelaksanaan.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($projects as $project)
                        @php
                            $latestProgress = $project->progresses()->latest()->first();
                            $progressPercent = $latestProgress ? $latestProgress->percentage : 0;
                            $latestStatus = $latestProgress ? $latestProgress->status : 'pending';
                        @endphp
                        <div class="bg-gray-50/50 hover:bg-white rounded-[2.5rem] p-8 border border-gray-100 hover:border-indigo-100 hover:shadow-xl transition-all flex flex-col justify-between group">
                            <div>
                                <!-- Project Title and ID -->
                                <div class="flex justify-between items-start mb-6">
                                    <div class="h-12 w-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-black shadow-inner">
                                        📦
                                    </div>
                                    <span class="text-[9px] bg-slate-900 text-white px-3 py-1.5 rounded-xl font-bold uppercase tracking-wider">
                                        ID: #PR-{{ $project->id }}
                                    </span>
                                </div>

                                <h3 class="font-black text-gray-900 text-xl tracking-tight mb-2 group-hover:text-indigo-600 transition-colors">
                                    {{ $project->item_name }}
                                </h3>
                                <p class="text-xs text-gray-500 font-medium mb-6 line-clamp-2">
                                    {{ $project->description ?? 'Tidak ada deskripsi proyek.' }}
                                </p>

                                <!-- Details List -->
                                <div class="space-y-3 mb-8 border-t border-b border-gray-100/80 py-4">
                                    <div class="flex justify-between text-xs font-semibold">
                                        <span class="text-gray-400 uppercase tracking-wider">Pelaksana / Vendor</span>
                                        <span class="text-gray-800 font-bold">{{ $project->vendor->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs font-semibold">
                                        <span class="text-gray-400 uppercase tracking-wider">Total Nilai Proyek</span>
                                        <span class="text-indigo-600 font-black">Rp {{ number_format($project->total_price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs font-semibold">
                                        <span class="text-gray-400 uppercase tracking-wider">Status Terakhir</span>
                                        <span>
                                            @if($latestStatus === 'approved')
                                                <span class="bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Verified</span>
                                            @elseif($latestStatus === 'anomaly')
                                                <span class="bg-red-50 text-red-700 border border-red-200 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest animate-pulse">Anomaly</span>
                                            @elseif($latestStatus === 'rejected')
                                                <span class="bg-red-100 text-red-800 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Rejected</span>
                                            @else
                                                <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Pending</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar and Action Button -->
                            <div>
                                <div class="mb-6">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Persentase Pengerjaan</span>
                                        <span class="text-base font-black text-indigo-600">{{ $progressPercent }}%</span>
                                    </div>
                                    <div class="h-2.5 w-full bg-gray-200/50 rounded-full overflow-hidden p-[1px]">
                                        <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full shadow-[0_0_10px_rgba(79,70,229,0.3)] transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                                    </div>
                                </div>

                                <a href="{{ route('progress.show', $project->id) }}" class="block w-full py-4 bg-slate-900 hover:bg-indigo-600 text-white font-black text-center rounded-2xl text-xs uppercase tracking-widest transition-all active:scale-[0.99] shadow-lg shadow-gray-200 hover:shadow-indigo-200">
                                    Detail Progres & Bukti
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-1 md:col-span-2 lg:col-span-3 py-20 text-center">
                            <div class="inline-block p-10 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[3rem]">
                                <span class="text-4xl mb-4 block">📭</span>
                                <p class="text-gray-500 font-black uppercase tracking-widest text-xs italic">Tidak ada proyek aktif yang ditemukan.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
