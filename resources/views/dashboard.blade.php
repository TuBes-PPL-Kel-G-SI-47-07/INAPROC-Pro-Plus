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

            {{-- ERROR HANDLING --}}
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-xl shadow-sm" role="alert">
                    <p class="font-bold">Terjadi Kesalahan:</p>
                    <ul class="text-sm list-disc ml-5 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="p-8 text-gray-900">
                    
                    {{-- ========================================== --}}
                    {{-- SEKSI ADMINISTRATOR (PBI 04, 08, 09, 11)   --}}
                    {{-- ========================================== --}}
                    @role('admin')
                        <div class="mb-10 flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Halo, Administrator!</h1>
                                <p class="text-gray-500 mt-1 font-medium">Kelola ekosistem pengadaan dan hitung objektifitas tender.</p>
                            </div>
                            <div class="hidden md:block">
                                <span class="bg-indigo-600 text-white px-4 py-2 rounded-full text-xs font-bold shadow-lg shadow-indigo-200 uppercase tracking-widest">System Controller</span>
                            </div>
                        </div>

                        {{-- TENDER PUBLICATION (PBI 08 & 09) --}}
                        <div class="bg-white p-8 rounded-[2.5rem] border-2 border-indigo-50 shadow-sm relative overflow-hidden mb-12">
                            <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                                <span class="mr-3 bg-indigo-50 p-2 rounded-xl text-indigo-600">🏗️</span> 
                                {{ __('Penerbitan Tender & Konfigurasi Bobot') }}
                            </h3>

                            <form action="{{ route('tender-config.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="judul_tender" :value="__('Judul Paket Pengadaan')" />
                                        <x-text-input name="judul_tender" class="mt-2 block w-full bg-gray-50 border-none rounded-2xl" placeholder="Contoh: Infrastruktur Jaringan Data" required />
                                    </div>
                                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100">
                                        <x-input-label class="text-amber-700" :value="__('Dokumen TOR/KAK (PBI-09)')" />
                                        <input type="file" name="tor_file" class="mt-2 block w-full text-xs" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                                    <div class="p-5 bg-blue-50/50 rounded-3xl border border-blue-100">
                                        <x-input-label class="text-blue-700 font-bold" :value="__('Harga (%)')" />
                                        <x-text-input type="number" name="weight_harga" class="mt-2 w-full border-none rounded-xl text-center font-black" value="40" required />
                                    </div>
                                    <div class="p-5 bg-purple-50/50 rounded-3xl border border-purple-100">
                                        <x-input-label class="text-purple-700 font-bold" :value="__('Teknis (%)')" />
                                        <x-text-input type="number" name="weight_teknis" class="mt-2 w-full border-none rounded-xl text-center font-black" value="40" required />
                                    </div>
                                    <div class="p-5 bg-emerald-50/50 rounded-3xl border border-emerald-100">
                                        <x-input-label class="text-emerald-700 font-bold" :value="__('Integritas (%)')" />
                                        <x-text-input type="number" name="weight_integritas" class="mt-2 w-full border-none rounded-xl text-center font-black" value="20" required />
                                    </div>
                                </div>

                                <x-primary-button class="w-full justify-center py-4 bg-slate-900 rounded-2xl shadow-xl">
                                    {{ __('Publikasikan Paket Tender') }}
                                </x-primary-button>
                            </form>
                        </div>

                        {{-- MATRIKS KOMPARASI & AUTO-SCORING (PBI-11) --}}
                        <div class="mt-12 bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-2xl font-black text-gray-900 flex items-center">
                                    <span class="mr-3 bg-indigo-600 text-white p-2 rounded-xl">📊</span> 
                                    {{ __('Matriks Komparasi & Auto-Scoring Engine') }}
                                </h3>
                                <span class="bg-indigo-100 text-indigo-700 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">DSS Algorithm Active</span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-[10px] tracking-widest">
                                        <tr>
                                            <th class="px-6 py-4">Nama Vendor</th>
                                            <th class="px-6 py-4 text-center">Harga</th>
                                            <th class="px-6 py-4 text-center">Teknis</th>
                                            <th class="px-6 py-4 text-center">Integritas</th>
                                            <th class="px-6 py-4 text-right text-indigo-600">Total Skor</th>
                                            <th class="px-6 py-4 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse(\App\Models\Bid::with('user')->get() as $bid)
                                        <tr class="hover:bg-indigo-50/30 transition-colors">
                                            <td class="px-6 py-4 font-bold text-gray-800">{{ $bid->user->name }}</td>
                                            <td class="px-6 py-4 text-center font-medium">{{ number_format($bid->score_harga, 2) ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-center font-medium">{{ number_format($bid->score_teknis, 2) ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-center font-medium">{{ number_format($bid->score_integritas, 2) ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-right font-black text-lg text-indigo-600">
                                                {{ number_format($bid->final_score, 2) ?? '0.00' }}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <form action="{{ route('bid.calculate', $bid->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-[10px] font-black hover:bg-indigo-700 transition-all shadow-md active:scale-95">
                                                        CALCULATE
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr><td colspan="6" class="py-12 text-center text-gray-400 italic">Belum ada penawaran (bids) yang masuk untuk dihitung.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endrole

                    {{-- ========================================== --}}
                    {{-- SEKSI VENDOR (PBI 03, 06, 07, 10)          --}}
                    {{-- ========================================== --}}
                    @role('vendor')
                        <div class="mb-10 grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <div class="lg:col-span-2 bg-gradient-to-br from-slate-900 via-indigo-950 to-black rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                                <div class="relative z-10 h-full flex flex-col justify-between">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-[10px] font-bold text-indigo-400 tracking-[0.3em] uppercase opacity-80 italic underline">Verified Strategic Partner</p>
                                            <h2 class="text-4xl font-black mt-2 uppercase tracking-tighter">{{ Auth::user()->name }}</h2>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[9px] bg-white/5 px-3 py-1.5 rounded-full border border-white/10 font-mono">UID: {{ Auth::user()->id }}-2026</span>
                                        </div>
                                    </div>
                                    <div class="mt-16 flex items-center bg-white/5 p-6 rounded-3xl border border-white/5 backdrop-blur-sm">
                                        <div class="h-10 w-10 bg-green-500 rounded-xl flex items-center justify-center shadow-lg transform rotate-3 mr-4">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <p class="text-green-400 font-black text-xl uppercase">Status: {{ Auth::user()->status ?? 'ACTIVE' }}</p>
                                    </div>
                                </div>
                                <div class="absolute -bottom-20 -right-20 h-64 w-64 bg-indigo-600/10 rounded-full blur-[80px]"></div>
                            </div>

                            <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-xl flex flex-col justify-center">
                                <h3 class="font-black text-xl mb-4 italic">🎯 Quick Metrics</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center border-b border-white/10 pb-2">
                                        <span class="text-xs">Bidding Encrypted</span>
                                        <span class="text-xs font-black uppercase">Active</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs">Pagu Guard</span>
                                        <span class="text-xs font-black uppercase">Enabled</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SEALED BIDDING PORTAL (PBI-10) --}}
                        <div class="bg-gray-900 p-8 rounded-[2.5rem] text-white shadow-2xl border border-white/5 relative overflow-hidden mb-12">
                            <h3 class="text-2xl font-black flex items-center mb-8">
                                <span class="mr-3 bg-white/10 p-2 rounded-xl text-indigo-400">🔐</span> 
                                {{ __('Sealed Bidding Portal') }}
                            </h3>

                            <form action="{{ route('bid.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <x-input-label class="text-gray-400 mb-2" :value="__('Pilih Paket Tender Aktif')" />
                                        <select name="tender_config_id" class="w-full bg-white/5 border-white/10 rounded-2xl text-white py-4 focus:ring-indigo-500" required>
                                            <option value="" class="text-black">-- Pilih Tender --</option>
                                            @foreach(\App\Models\TenderConfig::all() as $tender)
                                                <option value="{{ $tender->id }}" class="text-black">{{ $tender->judul_tender }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label class="text-gray-400 mb-2" :value="__('Nilai Penawaran (Price)')" />
                                        <div class="relative">
                                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-indigo-400 font-bold">Rp</span>
                                            <input type="number" name="offered_price" class="w-full bg-white/5 border-white/10 rounded-2xl text-white py-4 pl-14 focus:ring-indigo-500" placeholder="0" required />
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl transition-all">
                                    KIRIM & KUNCI PENAWARAN (ENCRYPTED)
                                </button>
                            </form>
                        </div>

                        {{-- SMART PROCUREMENT FORM (PBI 06 & 07) --}}
                        <div class="bg-gray-50 p-10 rounded-[3rem] border-2 border-dashed border-gray-200">
                            <h3 class="text-2xl font-black text-gray-900 flex items-center mb-8">
                                <span class="mr-3 bg-white p-2 rounded-2xl shadow-sm text-indigo-600">📦</span> {{ __('Permintaan Pengadaan Cerdas') }}
                            </h3>
                            <form action="{{ route('procurement.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <div class="max-w-md mb-8 p-6 bg-white rounded-3xl shadow-sm">
                                    <x-input-label for="budget_id" :value="__('Gunakan Plafon Pagu')" />
                                    <select name="budget_id" id="budget_id" class="mt-3 block w-full border-gray-200 rounded-xl focus:ring-indigo-500" required>
                                        @foreach(\App\Models\Budget::all() as $budget)
                                            <option value="{{ $budget->id }}">{{ $budget->nama_pagu }} (Sisa: Rp {{ number_format($budget->sisa_pagu) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <x-text-input name="item_name" placeholder="Item / Jasa" required class="rounded-2xl border-none shadow-sm py-4" />
                                    <x-text-input name="quantity" type="number" placeholder="Kuantitas" required class="rounded-2xl border-none shadow-sm py-4" />
                                    <x-text-input name="price" type="number" placeholder="Harga Satuan" required class="rounded-2xl border-none shadow-sm py-4" />
                                </div>
                                <x-primary-button class="bg-indigo-600 py-4 px-12 rounded-2xl">AJUKAN SEKARANG</x-primary-button>
                            </form>
                        </div>
                    @endrole

                </div>
            </div>
        </div>
    </div>
</x-app-layout>