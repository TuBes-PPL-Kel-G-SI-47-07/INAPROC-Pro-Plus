<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Executive Analytics & Forensic Audit') }}
        </h2>
    </x-slot>

    <!-- Leaflet.js CSS & JS CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <!-- ApexCharts JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- HEADER BANNER --}}
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-[3rem] p-8 md:p-12 text-white shadow-2xl relative overflow-hidden group">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <span class="bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest">
                            Audit Control & Business Intelligence
                        </span>
                        <h1 class="text-4xl md:text-5xl font-black mt-4 tracking-tighter leading-none">
                            EXECUTIVE AUDIT PORTAL
                        </h1>
                        <p class="text-slate-400 mt-3 max-w-xl text-sm font-medium">
                            Pemantauan menyeluruh efisiensi anggaran, deteksi anomali lelang, dan verifikasi profil spasial penyedia barang/jasa secara real-time.
                        </p>
                    </div>
                    <div class="flex items-center gap-4 bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-3xl">
                        <div class="text-5xl">🛡️</div>
                        <div>
                            <p class="text-xs uppercase text-slate-400 font-bold tracking-widest">Security Level</p>
                            <p class="text-xl font-black text-emerald-400">IMMUTABLE LOGS</p>
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-indigo-600/10 rounded-full blur-[100px]"></div>
            </div>

            {{-- METRICS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- STAT CARD 1 --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl flex items-center justify-between group hover:border-indigo-100 transition-all">
                    <div>
                        <p class="text-slate-500 text-xs font-black uppercase tracking-wider">Total Saving Pengadaan</p>
                        <p class="text-3xl font-black text-indigo-900 mt-2 tracking-tight">Rp {{ number_format($totalSavings, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-green-600 font-bold mt-1">✓ Efisiensi penawaran terpilih</p>
                    </div>
                    <div class="text-4xl bg-indigo-50 p-4 rounded-3xl group-hover:scale-110 transition-transform">💰</div>
                </div>

                {{-- STAT CARD 2 --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl flex items-center justify-between group hover:border-emerald-100 transition-all">
                    <div>
                        <p class="text-slate-500 text-xs font-black uppercase tracking-wider">Proyek Selesai (BAST)</p>
                        <p class="text-3xl font-black text-emerald-900 mt-2 tracking-tight">{{ $completedProjects }} Proyek</p>
                        <p class="text-[10px] text-emerald-600 font-bold mt-1">✓ Berhasil serah terima</p>
                    </div>
                    <div class="text-4xl bg-emerald-50 p-4 rounded-3xl group-hover:scale-110 transition-transform">🏆</div>
                </div>

                {{-- STAT CARD 3 --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl flex items-center justify-between group hover:border-red-100 transition-all">
                    <div>
                        <p class="text-slate-500 text-xs font-black uppercase tracking-wider">Indikasi Anomali Harga</p>
                        <p class="text-3xl font-black text-red-900 mt-2 tracking-tight">{{ count($detectedAnomalies) }} Temuan</p>
                        <p class="text-[10px] text-red-600 font-bold mt-1">⚠ Deviasi penawaran > 30%</p>
                    </div>
                    <div class="text-4xl bg-red-50 p-4 rounded-3xl group-hover:scale-110 transition-transform">🚨</div>
                </div>
            </div>

            {{-- VISUAL CHARTS GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                {{-- BUDGET EFFICIENCY CHART --}}
                <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-xl">
                    <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                        <span class="mr-3 bg-indigo-50 p-2.5 rounded-2xl text-indigo-600">📊</span>
                        {{ __('Grafik Efisiensi Pagu Anggaran') }}
                    </h3>
                    <div id="budgetChart" class="h-80"></div>
                </div>

                {{-- COLLUSION & ANOMALY DETECTOR --}}
                <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-xl flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 mb-2 flex items-center">
                            <span class="mr-3 bg-red-50 p-2.5 rounded-2xl text-red-600">🚨</span>
                            {{ __('Collusion & Price Anomaly Alert') }}
                        </h3>
                        <p class="text-xs text-gray-500 mb-6">Penawaran harga dari vendor yang melenceng signifikan dibanding rata-rata penawaran paket tender.</p>
                    </div>
                    
                    <div class="overflow-y-auto max-h-72 space-y-4 pr-2">
                        @forelse($detectedAnomalies as $anomaly)
                            <div class="p-5 rounded-2xl border border-red-100 bg-red-50/50 flex flex-col justify-between gap-2 relative overflow-hidden group">
                                <div class="flex justify-between items-start z-10">
                                    <div>
                                        <span class="bg-red-200 text-red-800 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider">
                                            {{ $anomaly['type'] }}
                                        </span>
                                        <h4 class="font-black text-slate-800 mt-2 text-sm">{{ $anomaly['tender_title'] }}</h4>
                                        <p class="text-xs text-slate-600 mt-1">Vendor: <span class="font-bold text-slate-900">{{ $anomaly['vendor_name'] }}</span></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-black text-red-600">Deviasi: {{ number_format($anomaly['deviation'], 1) }}%</p>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center text-xs mt-3 pt-2 border-t border-red-100/50">
                                    <span class="text-slate-500">Harga: <strong class="text-slate-800">Rp {{ number_format($anomaly['price'], 0, ',', '.') }}</strong></span>
                                    <span class="text-slate-500">Rerata: <strong class="text-slate-800">Rp {{ number_format($anomaly['average'], 0, ',', '.') }}</strong></span>
                                </div>
                            </div>
                        @empty
                            <div class="py-16 text-center border-2 border-dashed border-slate-200 rounded-3xl">
                                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs italic">No anomalies detected in database</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- GEOSPATIAL MAP CARD --}}
            <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-xl">
                <h3 class="text-xl font-black text-gray-900 mb-2 flex items-center">
                    <span class="mr-3 bg-emerald-50 p-2.5 rounded-2xl text-emerald-600">🗺️</span>
                    {{ __('Peta Sebaran Geografis Kantor Vendor') }}
                </h3>
                <p class="text-xs text-gray-500 mb-6">Visualisasi koordinat fisik (geotagging) kantor operasional vendor untuk menghindari penggunaan alamat fiktif / perusahaan cangkang.</p>
                
                <div id="vendorMap" class="h-[450px] w-full rounded-[2.5rem] border border-slate-200 shadow-inner z-0"></div>
            </div>

            {{-- IMMUTABLE AUDIT TRAIL LOGS --}}
            <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-xl">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 flex items-center">
                            <span class="mr-3 bg-slate-100 p-2.5 rounded-2xl text-slate-700">🔒</span>
                            {{ __('Immutable System Audit Trail') }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Jejak digital mutlak yang mencatat pelaku, aksi, alamat IP, dan table target secara permanen.</p>
                    </div>
                    <div>
                        <span class="bg-slate-900 text-white px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-wider border-b-4 border-slate-950">
                            Total Records: {{ $activityLogs->total() }}
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-3xl border border-slate-100">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 font-bold text-slate-600 uppercase text-[10px] tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left">Timestamp</th>
                                <th scope="col" class="px-6 py-4 text-left">Aktor / Pengguna</th>
                                <th scope="col" class="px-6 py-4 text-left">Aksi</th>
                                <th scope="col" class="px-6 py-4 text-left">Alamat IP</th>
                                <th scope="col" class="px-6 py-4 text-left">Target Data</th>
                                <th scope="col" class="px-6 py-4 text-left">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($activityLogs as $log)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                                        <span class="block text-[10px] text-indigo-400 font-bold">{{ $log->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-900">{{ $log->user->name ?? 'System' }}</span>
                                            <span class="text-[10px] text-slate-400 uppercase tracking-widest font-black">
                                                {{ $log->user ? $log->user->roles->pluck('name')->first() : 'System' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-indigo-50 border border-indigo-100 text-indigo-700">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-slate-500">
                                        {{ $log->ip_address ?? '127.0.0.1' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-600 font-mono">
                                        {{ $log->table_affected ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold text-slate-800">
                                        {{ $log->description }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-bold italic">
                                        Tidak ada jejak log aktivitas audit trail.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-6">
                    {{ $activityLogs->links() }}
                </div>
            </div>

        </div>
    </div>

    {{-- APEXCHARTS CONFIGURATION --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data for Budget Efficiency
            var budgetsData = @json($budgetsData);
            
            var names = budgetsData.map(function(item) { return item.name; });
            var initials = budgetsData.map(function(item) { return item.initial; });
            var finals = budgetsData.map(function(item) { return item.final; });

            var options = {
                series: [{
                    name: 'Estimasi Pengadaan (Awal)',
                    data: initials
                }, {
                    name: 'Nilai Kontrak Akhir',
                    data: finals
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    fontFamily: 'Inter, sans-serif',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 8,
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                colors: ['#6366f1', '#10b981'],
                xaxis: {
                    categories: names,
                    labels: {
                        style: {
                            fontWeight: 600
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Rupiah (Rp)',
                        style: {
                            fontWeight: 700
                        }
                    },
                    labels: {
                        formatter: function (value) {
                            return "Rp " + value.toLocaleString();
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rp " + val.toLocaleString()
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right'
                }
            };

            var chart = new ApexCharts(document.querySelector("#budgetChart"), options);
            chart.render();
        });
    </script>

    {{-- LEAFLET GEOSPATIAL MAP CONFIGURATION --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Default center is Jakarta
            var map = L.map('vendorMap').setView([-6.2088, 106.8456], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var vendors = @json($vendors);
            var markersGroup = new L.featureGroup();

            vendors.forEach(function (vendor) {
                if (vendor.latitude && vendor.longitude) {
                    var conditionColor = vendor.office_condition === 'Layak' ? 'emerald' : (vendor.office_condition === 'Tidak Layak' ? 'rose' : 'amber');
                    
                    var popupContent = `
                        <div style="font-family: 'Inter', sans-serif; padding: 4px;">
                            <h4 style="margin: 0 0 8px 0; font-weight: 800; text-transform: uppercase; color: #1e1b4b;">\${vendor.name}</h4>
                            <p style="margin: 0 0 4px 0; font-size: 11px; color: #475569;"><strong>Email:</strong> \${vendor.email}</p>
                            <p style="margin: 0 0 4px 0; font-size: 11px; color: #475569;"><strong>Telp:</strong> \${vendor.phone_number || '-'}</p>
                            <p style="margin: 0 0 8px 0; font-size: 11px; color: #475569;"><strong>Alamat:</strong> \${vendor.address || '-'}</p>
                            <div style="display: flex; gap: 8px; margin-top: 8px; border-top: 1px solid #f1f5f9; padding-top: 8px;">
                                <span style="background-color: #f1f5f9; border: 1px solid #e2e8f0; padding: 3px 8px; border-radius: 9999px; font-size: 9px; font-weight: 700;">Score: \${vendor.survey_score}</span>
                                <span style="background-color: \${vendor.office_condition === 'Layak' ? '#dcfce7' : '#fee2e2'}; color: \${vendor.office_condition === 'Layak' ? '#15803d' : '#b91c1c'}; padding: 3px 8px; border-radius: 9999px; font-size: 9px; font-weight: 700;">Office: \${vendor.office_condition}</span>
                            </div>
                        </div>
                    `;

                    var marker = L.marker([vendor.latitude, vendor.longitude]).addTo(map);
                    marker.bindPopup(popupContent);
                    markersGroup.addLayer(marker);
                }
            });

            if (vendors.length > 0) {
                map.fitBounds(markersGroup.getBounds(), { padding: [50, 50] });
            }
        });
    </script>
</x-app-layout>
