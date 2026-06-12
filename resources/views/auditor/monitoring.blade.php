<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Analytics & Monitoring Dashboard') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Pantau performa pengadaan dan status tender secara real-time.</p>
            </div>
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                    <span class="flex w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                    System Online
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- WIDGETS RINGKASAN METRIK --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Widget 1: Tender Berjalan -->
                <div class="bg-white shadow-sm rounded-xl border border-slate-100 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tender Berjalan</p>
                            <h3 class="text-4xl font-bold text-slate-800">{{ $totalRunningTenders ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs text-emerald-600 font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        <span>Active</span>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-5 pointer-events-none">
                        <svg class="w-24 h-24 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
                
                <!-- Widget 2: Butuh Survey -->
                <div class="bg-white shadow-sm rounded-xl border border-slate-100 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Butuh Survey</p>
                            <h3 class="text-4xl font-bold text-slate-800">{{ $totalSurveyNeeded ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs text-amber-600 font-medium">
                        <span>Pending Action</span>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-5 pointer-events-none">
                        <svg class="w-24 h-24 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </div>
                
                <!-- Widget 3: Tender Selesai -->
                <div class="bg-white shadow-sm rounded-xl border border-slate-100 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tender Selesai</p>
                            <h3 class="text-4xl font-bold text-slate-800">{{ $totalCompletedTenders ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs text-emerald-600 font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Completed</span>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-5 pointer-events-none">
                        <svg class="w-24 h-24 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <!-- Widget 4: Efisiensi Anggaran (Visual metrics for Enterprise feel) -->
                <div class="bg-white shadow-sm rounded-xl border border-slate-100 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Efisiensi Anggaran</p>
                            <h3 class="text-4xl font-bold text-slate-800">12<span class="text-xl text-slate-500">%</span></h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs text-purple-600 font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        <span>Saved YTD</span>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-5 pointer-events-none">
                        <svg class="w-24 h-24 text-purple-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            {{-- CHART & ANALYTICS CONTAINER --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                <!-- Main Chart -->
                <div class="lg:col-span-2 bg-white shadow-sm rounded-xl border border-slate-100 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-800">Tren Nilai Pengadaan Bulanan</h3>
                        <div class="relative">
                            <select class="text-sm bg-slate-50 border border-slate-200 rounded-lg text-slate-600 focus:ring-blue-500 py-2 pl-4 pr-8 cursor-pointer font-medium outline-none">
                                <option>Tahun Ini (2026)</option>
                                <option>Tahun Lalu (2025)</option>
                            </select>
                        </div>
                    </div>
                    <!-- Chart.js Container -->
                    <div class="relative h-72 w-full flex items-center justify-center bg-slate-50/30 rounded-lg">
                        <canvas id="procurementChart"></canvas>
                        <!-- Fallback message -->
                        <div class="absolute inset-0 flex items-center justify-center text-slate-400 text-sm italic" id="chartFallback">
                            Memuat grafik analitik...
                        </div>
                    </div>
                </div>

                <!-- Secondary Chart / Breakdown -->
                <div class="bg-white shadow-sm rounded-xl border border-slate-100 p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-6">Distribusi Status Tender</h3>
                    <!-- Chart.js Container -->
                    <div class="relative h-56 w-full flex items-center justify-center">
                        <canvas id="statusChart"></canvas>
                        <!-- Fallback -->
                        <div class="absolute inset-0 flex items-center justify-center text-slate-400 text-sm italic" id="statusChartFallback">
                            Memuat grafik...
                        </div>
                    </div>
                    
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-blue-500 mr-2 shadow-sm shadow-blue-200"></span>
                                <span class="text-slate-600 font-medium">Open / Berjalan</span>
                            </div>
                            <span class="font-bold text-slate-800">45%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 mr-2 shadow-sm shadow-emerald-200"></span>
                                <span class="text-slate-600 font-medium">Completed</span>
                            </div>
                            <span class="font-bold text-slate-800">35%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-amber-500 mr-2 shadow-sm shadow-amber-200"></span>
                                <span class="text-slate-600 font-medium">Pending / Draft</span>
                            </div>
                            <span class="font-bold text-slate-800">20%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL PEMANTAUAN UTAMA --}}
            <div class="bg-white shadow-sm rounded-xl border border-slate-100 overflow-hidden mt-8">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/80 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Pemantauan Paket Pengadaan</h3>
                    <button class="text-sm bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-lg hover:bg-slate-50 transition-colors shadow-sm font-medium">
                        View All
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider">Nama Paket Pengadaan</th>
                                <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider">Pagu Anggaran</th>
                                <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider text-center">Jumlah Bids</th>
                                <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider">Status Progres</th>
                                <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider text-center">Aksi Cepat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($procurements ?? [] as $req)
                                <tr class="hover:bg-slate-50 transition-colors border-b border-slate-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-slate-800">{{ $req->item_name }}</div>
                                        <div class="text-xs text-slate-500 font-mono mt-1">PR-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-slate-700">Rp {{ number_format($req->budget->nominal_awal ?? $req->total_price, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200">
                                            {{ $req->tender ? $req->tender->bids_count : 0 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($req->status === 'pending')
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-700">Pending</span>
                                        @elseif($req->status === 'rejected')
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-700">Rejected</span>
                                        @elseif($req->status === 'approved' && !$req->tender)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-700">Wait Tender</span>
                                        @elseif($req->tender)
                                            @if($req->tender->status === 'open')
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-700">Open</span>
                                            @elseif($req->tender->status === 'closed')
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-700">Closed</span>
                                            @elseif($req->tender->status === 'completed')
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-700">Completed</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-100 text-slate-700">{{ ucfirst($req->tender->status) }}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        @if($req->tender)
                                            <a href="{{ url('/dashboard?tender_id=' . $req->tender->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-sm">
                                                <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                View
                                            </a>
                                        @else
                                            <a href="{{ route('procurement.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-sm">
                                                Lihat PR
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center">
                                        <p class="text-slate-500 text-sm font-medium italic">Belum ada data pengadaan atau tender tercatat.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide fallbacks
            const fallbacks = document.querySelectorAll('#chartFallback, #statusChartFallback');
            fallbacks.forEach(f => f.style.display = 'none');

            // Setup Chart Defaults for Enterprise Look
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#64748b'; // slate-500
            
            // 1. Line Chart (Tren Pengadaan)
            const ctxProcurement = document.getElementById('procurementChart').getContext('2d');
            
            // Gradient for Line Chart
            let gradientBlue = ctxProcurement.createLinearGradient(0, 0, 0, 300);
            gradientBlue.addColorStop(0, 'rgba(37, 99, 235, 0.15)'); // blue-600
            gradientBlue.addColorStop(1, 'rgba(37, 99, 235, 0)');

            new Chart(ctxProcurement, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Nilai Pengadaan (Miliar Rp)',
                        data: [12, 19, 15, 25, 22, 30, 28],
                        borderColor: '#2563eb', // blue-600
                        backgroundColor: gradientBlue,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b', // slate-800
                            titleFont: { size: 13, family: 'Inter' },
                            bodyFont: { size: 14, family: 'Inter', weight: 'bold' },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9', // slate-100
                                drawBorder: false
                            },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            border: { display: false }
                        }
                    }
                }
            });

            // 2. Doughnut Chart (Status)
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Open', 'Completed', 'Pending'],
                    datasets: [{
                        data: [45, 35, 20],
                        backgroundColor: [
                            '#3b82f6', // blue-500
                            '#10b981', // emerald-500
                            '#f59e0b'  // amber-500
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            cornerRadius: 8,
                            bodyFont: { size: 14, weight: 'bold' }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
