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

            {{-- ERROR BUDGET (PBI-07) & TOTAL WEIGHT (PBI-08) --}}
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-xl shadow-sm" role="alert">
                    <p class="font-bold">Perhatian!</p>
                    <ul class="text-sm list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="p-8 text-gray-900">
                    
                    {{-- ========================================== --}}
                    {{-- SEKSI ADMIN / SURVEYOR (PBI-04, 08, 09)    --}}
                    {{-- ========================================== --}}
                    @role('admin')
                        <div class="mb-8">
                            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Halo, Administrator!</h1>
                            <p class="text-gray-500 mt-1 font-medium">Kelola verifikasi lapangan dan publikasi dokumen lelang secara real-time.</p>
                        </div>

                        {{-- FORM SURVEY (PBI-04) --}}
                        <div class="bg-gray-50 p-8 rounded-3xl border border-gray-200 shadow-inner mb-8">
                            <h3 class="text-lg font-bold mb-6 text-gray-800 flex items-center">
                                <span class="mr-3 bg-indigo-600 text-white p-2 rounded-lg shadow-lg shadow-indigo-200">📋</span> 
                                {{ __('Input Laporan Survey Lapangan') }}
                            </h3>
                            
                            <form action="{{ route('survey.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="user_id" :value="__('Pilih Vendor Strategis')" />
                                        <select name="user_id" id="user_id" class="mt-2 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500" required>
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
                                <x-primary-button class="w-full justify-center py-4 bg-indigo-600 rounded-2xl transition-all">
                                    {{ __('Finalisasi & Verifikasi Vendor') }}
                                </x-primary-button>
                            </form>
                        </div>

                        {{-- FORM KONFIGURASI TENDER & UPLOAD TOR (PBI-08 & PBI-09) --}}
                        <div class="bg-white p-8 rounded-3xl border-2 border-indigo-50 shadow-sm">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-black text-gray-900 flex items-center">
                                    <span class="mr-3 bg-indigo-50 p-2 rounded-xl text-indigo-600">⚖️</span> 
                                    {{ __('Penerbitan Tender & Bobot Skor') }}
                                </h3>
                                <div class="space-x-2">
                                    <span class="text-[9px] bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-bold uppercase">PBI-08</span>
                                    <span class="text-[9px] bg-amber-100 text-amber-700 px-3 py-1 rounded-full font-bold uppercase">PBI-09</span>
                                </div>
                            </div>

                            <form action="{{ route('tender-config.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <div>
                                    <x-input-label for="judul_tender" :value="__('Nama Paket Tender / Proyek')" />
                                    <x-text-input name="judul_tender" class="mt-2 block w-full bg-gray-50 border-none rounded-2xl" placeholder="Contoh: Pengadaan Server Cloud Tahap 1" required />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="p-6 bg-blue-50/50 rounded-3xl border border-blue-100">
                                        <x-input-label class="text-blue-700 font-bold" :value="__('Bobot Harga (%)')" />
                                        <x-text-input type="number" name="weight_harga" class="mt-2 w-full border-none rounded-xl text-center font-black" value="40" required />
                                    </div>
                                    <div class="p-6 bg-purple-50/50 rounded-3xl border border-purple-100">
                                        <x-input-label class="text-purple-700 font-bold" :value="__('Bobot Teknis (%)')" />
                                        <x-text-input type="number" name="weight_teknis" class="mt-2 w-full border-none rounded-xl text-center font-black" value="40" required />
                                    </div>
                                    <div class="p-6 bg-emerald-50/50 rounded-3xl border border-emerald-100">
                                        <x-input-label class="text-emerald-700 font-bold" :value="__('Bobot Integritas (%)')" />
                                        <x-text-input type="number" name="weight_integritas" class="mt-2 w-full border-none rounded-xl text-center font-black" value="20" required />
                                    </div>
                                </div>

                                {{-- UPLOAD REPOSITORY TOR/KAK (PBI-09) --}}
                                <div class="p-6 bg-amber-50/40 rounded-3xl border border-amber-100 border-dashed">
                                    <x-input-label class="text-amber-700 font-bold flex items-center" for="tor_file">
                                        <span>📂 {{ __('Unggah Dokumen Spesifikasi (TOR/KAK)') }}</span>
                                    </x-input-label>
                                    <input type="file" name="tor_file" id="tor_file" class="mt-3 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer" required>
                                    <p class="text-[10px] text-amber-600 mt-2 font-medium italic">*Format PDF/DOCX (Maks 5MB) - Dokumen akan diakses oleh seluruh Vendor.</p>
                                </div>

                                <x-primary-button class="w-full justify-center py-4 bg-slate-900 hover:bg-black shadow-xl rounded-2xl transition-all active:scale-[0.98]">
                                    {{ __('Publikasikan Paket Tender') }}
                                </x-primary-button>
                            </form>
                        </div>

                        {{-- TABEL MONITORING SURVEY --}}
                        <div class="mt-12">
                            <h3 class="text-xl font-extrabold text-gray-900 mb-6">Log Aktivitas Survey Terbaru</h3>
                            <div class="overflow-x-auto rounded-3xl border border-gray-100 shadow-sm">
                                <table class="min-w-full bg-white text-sm">
                                    <thead class="bg-gray-50 border-b border-gray-100">
                                        <tr class="text-gray-500 font-bold">
                                            <th class="py-4 px-6 text-left">Vendor</th>
                                            <th class="py-4 px-6 text-center">Skor Digital</th>
                                            <th class="py-4 px-6 text-center">Bukti Visual</th>
                                            <th class="py-4 px-6 text-right">Keputusan Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @forelse($surveyReports ?? [] as $report)
                                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                                <td class="py-4 px-6 font-bold text-gray-800">{{ $report->vendor->name }}</td>
                                                <td class="py-4 px-6 text-center font-black text-indigo-600">{{ $report->infrastructure_score }}%</td>
                                                <td class="py-4 px-6 text-center">
                                                    <img src="{{ asset('storage/' . $report->survey_photo) }}" class="h-10 w-16 object-cover mx-auto rounded-lg">
                                                </td>
                                                <td class="py-4 px-6 text-right font-bold uppercase text-[10px] {{ $report->infrastructure_score >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $report->infrastructure_score >= 70 ? 'Verified' : 'Rejected' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="py-12 text-center text-gray-400">Data masih kosong.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    @else
                        @role('vendor')
                            {{-- SEKSI VENDOR TETAP SAMA (DARI CODE ASLI LO) --}}
                            <div class="mb-10 grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <div class="lg:col-span-2 bg-gradient-to-br from-slate-900 via-indigo-950 to-black rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                                    <div class="relative z-10 h-full flex flex-col justify-between">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-[10px] font-bold text-indigo-400 tracking-[0.3em] uppercase opacity-80">Digital Vendor Certificate</p>
                                                <h2 class="text-4xl font-black mt-2 uppercase tracking-tighter">{{ Auth::user()->name }}</h2>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-[9px] bg-white/5 px-3 py-1.5 rounded-full border border-white/10 font-mono tracking-tighter">UID: {{ Auth::user()->id }}-2026</span>
                                            </div>
                                        </div>
                                        <div class="mt-16 flex items-center bg-white/5 p-6 rounded-3xl border border-white/5 backdrop-blur-sm">
                                            <p class="text-yellow-400 font-black text-2xl tracking-tight uppercase">Status: {{ Auth::user()->status ?? 'Pending' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-xl flex flex-col justify-center">
                                    <h3 class="font-black text-xl mb-4 italic">🎯 Quick Info</h3>
                                    <p class="text-xs leading-relaxed">Pastikan Pagu Budget tersedia dan cek Dokumen TOR/KAK pada pengumuman tender terbaru.</p>
                                </div>
                            </div>

                            {{-- FORM PENGADAAN (PBI-06 & 07) --}}
                            <div class="mt-12 bg-gray-50 p-8 rounded-[2rem] border-2 border-dashed border-gray-200">
                                <h3 class="text-2xl font-black text-gray-900 flex items-center mb-8">
                                    <span class="mr-3 bg-white p-2 rounded-xl shadow-sm text-indigo-600">📦</span> {{ __('Pengajuan Pengadaan Cerdas') }}
                                </h3>
                                <form action="{{ route('procurement.store') }}" method="POST" class="space-y-6">
                                    @csrf
                                    <div class="max-w-md mb-6">
                                        <x-input-label for="budget_id" :value="__('Pilih Sumber Dana (Pagu)')" />
                                        <select name="budget_id" id="budget_id" class="mt-2 block w-full border-gray-300 rounded-xl" required>
                                            <option value="">-- Pilih Pagu Anggaran --</option>
                                            @foreach(\App\Models\Budget::all() as $budget)
                                                <option value="{{ $budget->id }}">{{ $budget->nama_pagu }} (Sisa: Rp {{ number_format($budget->sisa_pagu) }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <x-text-input name="item_name" placeholder="Item / Jasa" required class="rounded-2xl" />
                                        <x-text-input name="quantity" type="number" placeholder="Qty" required class="rounded-2xl" />
                                        <x-text-input name="price" type="number" placeholder="Harga Satuan" required class="rounded-2xl" />
                                    </div>
                                    <x-primary-button class="bg-indigo-600 py-4 px-10 rounded-2xl shadow-lg">Ajukan Sekarang</x-primary-button>
                                </form>
                            </div>
                        @endrole
                    @endrole

                </div>
            </div>
        </div>
    </div>
</x-app-layout>