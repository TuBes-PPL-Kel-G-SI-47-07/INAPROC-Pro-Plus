<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Utama INAPROC+') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- NOTIFIKASI SISTEM --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-xl shadow-sm animate-pulse" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            {{-- ERROR BUDGET (PBI-07) --}}
            @if($errors->has('budget_error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-xl shadow-sm" role="alert">
                    <p class="font-bold">Gagal Mengajukan!</p>
                    <p class="text-sm">{{ $errors->first('budget_error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="p-8 text-gray-900">
                    
                    {{-- ========================================== --}}
                    {{-- SEKSI ADMIN / SURVEYOR (PBI-04)           --}}
                    {{-- ========================================== --}}
                    @role('admin')
                        <div class="mb-8">
                            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Halo, Administrator!</h1>
                            <p class="text-gray-500 mt-1 font-medium">Kelola verifikasi lapangan dan pantau pengadaan sistem secara real-time.</p>
                        </div>

                        <div class="bg-gray-50 p-8 rounded-3xl border border-gray-200 shadow-inner">
                            <h3 class="text-lg font-bold mb-6 text-gray-800 flex items-center">
                                <span class="mr-3 bg-indigo-600 text-white p-2 rounded-lg shadow-lg shadow-indigo-200">📋</span> 
                                {{ __('Input Laporan Survey Lapangan') }}
                            </h3>
                            
                            <form action="{{ route('survey.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="user_id" :value="__('Pilih Vendor Strategis')" />
                                        <select name="user_id" id="user_id" class="mt-2 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                            <option value="">-- Pilih Vendor --</option>
                                            @foreach(\App\Models\User::role('vendor')->get() as $vendor)
                                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="office_condition" :value="__('Status Kelayakan Kantor')" />
                                        <select name="office_condition" id="office_condition" class="mt-2 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500" required>
                                            <option value="Layak">Layak / Sesuai Standar</option>
                                            <option value="Tidak Layak">Tidak Layak / Lokasi Fiktif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="infrastructure_score" :value="__('Skor Infrastruktur Digital (0-100)')" />
                                        <x-text-input type="number" name="infrastructure_score" class="mt-2 block w-full rounded-xl" placeholder="Contoh: 85" required min="0" max="100" />
                                    </div>
                                    <div>
                                        <x-input-label for="survey_photo" :value="__('Unggah Bukti Geotagging')" />
                                        <input type="file" name="survey_photo" id="survey_photo" class="mt-2 block w-full text-sm border border-gray-300 rounded-xl cursor-pointer bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="notes" :value="__('Catatan Audit Lapangan')" />
                                    <textarea name="notes" rows="3" class="mt-2 block w-full border-gray-300 rounded-2xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Berikan detail temuan di lapangan..."></textarea>
                                </div>

                                <x-primary-button class="w-full justify-center py-4 bg-indigo-600 hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all rounded-2xl">
                                    {{ __('Finalisasi & Verifikasi Vendor') }}
                                </x-primary-button>
                            </form>
                        </div>
                        {{-- FORM KONFIGURASI BOBOT TENDER (PBI-08) --}}
                        <div class="mt-8 bg-white p-8 rounded-3xl border border-indigo-100 shadow-sm">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-black text-gray-900 flex items-center">
                                    <span class="mr-3 bg-indigo-50 p-2 rounded-xl text-indigo-600">⚖️</span> 
                                    {{ __('Konfigurasi Bobot Penilaian Tender') }}
                                </h3>
                                <span class="text-[10px] bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-bold uppercase">Sprint 2: PBI-08</span>
                            </div>

                            @if($errors->has('total_weight'))
                                <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-2xl border border-red-100 flex items-center">
                                    <span class="mr-2">⚠️</span> {{ $errors->first('total_weight') }}
                                </div>
                            @endif

                            <form action="{{ route('tender-config.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <div>
                                    <x-input-label for="judul_tender" :value="__('Nama Paket Tender / Proyek')" />
                                    <x-text-input name="judul_tender" class="mt-2 block w-full bg-gray-50 border-none rounded-2xl" placeholder="Contoh: Pengadaan Server Cloud Tahap 1" required />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="p-6 bg-blue-50/50 rounded-3xl border border-blue-100">
                                        <x-input-label class="text-blue-700 font-bold" :value="__('Bobot Harga (%)')" />
                                        <x-text-input type="number" name="weight_harga" class="mt-2 w-full border-none rounded-xl" value="40" required min="0" max="100" />
                                    </div>
                                    <div class="p-6 bg-purple-50/50 rounded-3xl border border-purple-100">
                                        <x-input-label class="text-purple-700 font-bold" :value="__('Bobot Teknis (%)')" />
                                        <x-text-input type="number" name="weight_teknis" class="mt-2 w-full border-none rounded-xl" value="40" required min="0" max="100" />
                                    </div>
                                    <div class="p-6 bg-emerald-50/50 rounded-3xl border border-emerald-100">
                                        <x-input-label class="text-emerald-700 font-bold" :value="__('Bobot Integritas (%)')" />
                                        <x-text-input type="number" name="weight_integritas" class="mt-2 w-full border-none rounded-xl" value="20" required min="0" max="100" />
                                    </div>
                                </div>

                                <x-primary-button class="w-full justify-center py-4 bg-indigo-600 hover:bg-indigo-700 shadow-xl shadow-indigo-100 rounded-2xl transition-all">
                                    {{ __('Tetapkan Bobot Penilaian') }}
                                </x-primary-button>
                            </form>
                        </div>
                        {{-- TABEL MONITORING --}}
                        <div class="mt-12">
                            <h3 class="text-xl font-extrabold text-gray-900 mb-6">Log Aktivitas Survey Terbaru</h3>
                            <div class="overflow-x-auto rounded-3xl border border-gray-100 shadow-sm">
                                <table class="min-w-full bg-white text-sm">
                                    <thead class="bg-gray-50 border-b border-gray-100">
                                        <tr class="text-gray-500 font-bold">
                                            <th class="py-4 px-6 text-left uppercase tracking-wider">Vendor</th>
                                            <th class="py-4 px-6 text-center uppercase tracking-wider">Skor Digital</th>
                                            <th class="py-4 px-6 text-center uppercase tracking-wider">Bukti Visual</th>
                                            <th class="py-4 px-6 text-right uppercase tracking-wider">Keputusan Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @forelse($surveyReports ?? [] as $report)
                                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                                <td class="py-4 px-6 font-bold text-gray-800">{{ $report->vendor->name }}</td>
                                                <td class="py-4 px-6 text-center">
                                                    <div class="flex items-center justify-center">
                                                        <span class="text-indigo-600 font-black mr-1">{{ $report->infrastructure_score }}</span>
                                                        <span class="text-gray-400 text-[10px]">%</span>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6 text-center">
                                                    <img src="{{ asset('storage/' . $report->survey_photo) }}" class="h-10 w-16 object-cover mx-auto rounded-lg shadow-sm border border-white">
                                                </td>
                                                <td class="py-4 px-6 text-right">
                                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $report->infrastructure_score >= 70 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                        {{ $report->infrastructure_score >= 70 ? 'Verified' : 'Rejected' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="py-12 text-center text-gray-400 font-medium italic">Data survey masih kosong.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    @else
                        @role('vendor')
                            {{-- IDENTITAS VENDOR & STATUS --}}
                            <div class="mb-10 grid grid-cols-1 lg:grid-cols-3 gap-8">
                                {{-- KARTU SERTIFIKASI DIGITAL --}}
                                <div class="lg:col-span-2 bg-gradient-to-br from-slate-900 via-indigo-950 to-black rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                                    <div class="relative z-10 h-full flex flex-col justify-between">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-[10px] font-bold text-indigo-400 tracking-[0.3em] uppercase opacity-80">Digital Vendor Certificate</p>
                                                <h2 class="text-4xl font-black mt-2 uppercase tracking-tighter leading-none group-hover:text-indigo-300 transition-colors">{{ Auth::user()->name }}</h2>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-[9px] bg-white/5 px-3 py-1.5 rounded-full border border-white/10 font-mono tracking-tighter">UID: {{ Auth::user()->id }}-2026</span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-16 flex items-center bg-white/5 p-6 rounded-3xl border border-white/5 backdrop-blur-sm">
                                            @if(Auth::user()->status == 'verified')
                                                <div class="h-16 w-16 bg-green-500 rounded-2xl flex items-center justify-center shadow-2xl shadow-green-500/40 transform rotate-3">
                                                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                                <div class="ml-6">
                                                    <p class="text-green-400 font-black text-2xl tracking-tight uppercase">Status: Verified</p>
                                                    <p class="text-xs text-indigo-200/60 font-medium">Selamat! Perusahaan Anda lolos audit fisik INAPROC+.</p>
                                                </div>
                                            @elseif(Auth::user()->status == 'rejected')
                                                <div class="h-16 w-16 bg-red-600 rounded-2xl flex items-center justify-center shadow-2xl shadow-red-500/40">
                                                    <span class="text-3xl font-black text-white">!</span>
                                                </div>
                                                <div class="ml-6">
                                                    <p class="text-red-400 font-black text-2xl tracking-tight uppercase">Status: Rejected</p>
                                                    <p class="text-xs text-indigo-200/60 font-medium">Kelayakan kantor belum memenuhi standar minimal.</p>
                                                </div>
                                            @else
                                                <div class="h-16 w-16 bg-yellow-500 rounded-2xl flex items-center justify-center animate-pulse shadow-2xl shadow-yellow-500/40">
                                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </div>
                                                <div class="ml-6">
                                                    <p class="text-yellow-400 font-black text-2xl tracking-tight uppercase">Status: Pending</p>
                                                    <p class="text-xs text-indigo-200/60 font-medium">Mohon tunggu petugas surveyor menjadwalkan kunjungan.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="absolute -bottom-20 -right-20 h-64 w-64 bg-indigo-600/10 rounded-full blur-[80px]"></div>
                                </div>

                                {{-- QUICK INFO --}}
                                <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-100 flex flex-col justify-center">
                                    <h3 class="font-black text-xl mb-4 flex items-center italic">
                                        <span class="mr-2 not-italic">🎯</span> Quick Info
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex items-start">
                                            <span class="bg-white/20 p-2 rounded-lg mr-3 text-sm font-bold">1</span>
                                            <p class="text-xs font-medium leading-relaxed">Update Geotagging Office pada menu profil untuk akurasi survey.</p>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="bg-white/20 p-2 rounded-lg mr-3 text-sm font-bold">2</span>
                                            <p class="text-xs font-medium leading-relaxed">Pastikan Pagu Budget tersedia sebelum mengajukan pengadaan baru.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ========================================== --}}
                            {{-- FORM PENGADAAN BARANG (PBI-06 & PBI-07)   --}}
                            {{-- ========================================== --}}
                            <div class="mt-12 bg-gray-50 p-8 rounded-[2rem] border-2 border-dashed border-gray-200">
                                <div class="mb-8">
                                    <h3 class="text-2xl font-black text-gray-900 flex items-center">
                                        <span class="mr-3 bg-white p-2 rounded-xl shadow-sm text-indigo-600">📦</span> 
                                        {{ __('Pengajuan Pengadaan Cerdas') }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-2">Sistem akan melakukan <b>Automated Pagu Check</b> untuk memastikan ketersediaan dana.</p>
                                </div>

                                <form action="{{ route('procurement.store') }}" method="POST" class="space-y-6">
                                    @csrf
                                    
                                    {{-- DROPDOWN PAGU (PBI-07) --}}
                                    <div class="max-w-md">
                                        <x-input-label for="budget_id" :value="__('Pilih Sumber Dana (Pagu)')" />
                                        <select name="budget_id" id="budget_id" class="mt-2 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500" required>
                                            <option value="">-- Pilih Pagu Anggaran --</option>
                                            @foreach(\App\Models\Budget::all() as $budget)
                                                <option value="{{ $budget->id }}">
                                                    {{ $budget->nama_pagu }} (Sisa: Rp {{ number_format($budget->sisa_pagu) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <x-input-label for="item_name" :value="__('Item / Jasa')" />
                                            <x-text-input id="item_name" name="item_name" type="text" class="mt-2 block w-full rounded-2xl border-none shadow-sm focus:ring-2 focus:ring-indigo-500" required placeholder="Contoh: Alat Tulis Kantor" />
                                        </div>
                                        <div>
                                            <x-input-label for="quantity" :value="__('Kuantitas (Qty)')" />
                                            <x-text-input id="quantity" name="quantity" type="number" class="mt-2 block w-full rounded-2xl border-none shadow-sm" required min="1" placeholder="100" />
                                        </div>
                                        <div>
                                            <x-input-label for="price" :value="__('Estimasi Harga Satuan')" />
                                            <div class="relative">
                                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-xs">Rp</span>
                                                <x-text-input id="price" name="price" type="number" class="mt-2 block w-full rounded-2xl border-none shadow-sm pl-12" required min="0" placeholder="50.000" />
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <x-input-label for="description" :value="__('Justifikasi Kebutuhan')" />
                                        <textarea id="description" name="description" rows="2" class="mt-2 block w-full border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-indigo-500" placeholder="Jelaskan spesifikasi singkat atau alasan urgensi..."></textarea>
                                    </div>

                                    <div class="flex justify-end">
                                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 py-4 px-10 rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-95">
                                            {{ __('Ajukan Pengadaan Sekarang') }}
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>

                            {{-- UPLOAD PORTOFOLIO (PBI-03) --}}
                            <div class="mt-16 grid grid-cols-1 lg:grid-cols-3 gap-12">
                                <div class="lg:col-span-1">
                                    <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                                        <span class="mr-3 text-indigo-600">🖼️</span> {{ __('Update Visual Portfolio') }}
                                    </h3>
                                    <form action="{{ route('portfolio.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                                        @csrf
                                        <div>
                                            <x-input-label for="title" :value="__('Judul Proyek')" />
                                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full rounded-xl" required />
                                        </div>
                                        <div>
                                            <x-input-label for="portfolio_file" :value="__('File Visual')" />
                                            <input type="file" name="portfolio_file" id="portfolio_file" class="mt-1 block w-full text-xs" required>
                                        </div>
                                        <x-primary-button class="w-full justify-center rounded-xl bg-slate-900">Upload</x-primary-button>
                                    </form>
                                </div>

                                <div class="lg:col-span-2">
                                    <h3 class="text-xl font-black text-gray-900 mb-6">Galeri Hasil Kerja</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @forelse($portfolios ?? [] as $item)
                                            <div class="group relative aspect-square rounded-3xl overflow-hidden shadow-md border-4 border-white">
                                                @if($item->file_type === 'video')
                                                    <video class="w-full h-full object-cover" muted><source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4"></video>
                                                @else
                                                    <img src="{{ asset('storage/' . $item->file_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                                @endif
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-4">
                                                    <p class="text-white text-[10px] font-bold text-center leading-tight">{{ $item->title }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-span-full py-12 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100">
                                                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest italic">Belum Ada Portfolio</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endrole
                    @endrole

                </div>
            </div>
        </div>
    </div>
</x-app-layout>