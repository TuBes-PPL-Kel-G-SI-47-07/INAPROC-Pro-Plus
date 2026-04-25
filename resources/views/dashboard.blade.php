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

            {{-- MULTI-ERROR HANDLING (PBI 07-10) --}}
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
                    {{-- SEKSI ADMINISTRATOR (PBI-04, 08, 09)       --}}
                    {{-- ========================================== --}}
                    @role('admin')
                        <div class="mb-10 flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Halo, Administrator!</h1>
                                <p class="text-gray-500 mt-1 font-medium">Kelola ekosistem pengadaan dan transparansi tender.</p>
                            </div>
                            <div class="hidden md:block">
                                <span class="bg-indigo-600 text-white px-4 py-2 rounded-full text-xs font-bold shadow-lg shadow-indigo-200 uppercase tracking-widest">System Controller</span>
                            </div>
                        </div>

                        {{-- DUAL ACTION: TENDER PUBLICATION (PBI-08 & 09) --}}
                        <div class="bg-white p-8 rounded-[2.5rem] border-2 border-indigo-50 shadow-sm relative overflow-hidden">
                            <div class="relative z-10">
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

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="p-5 bg-blue-50/50 rounded-3xl border border-blue-100 text-center">
                                            <x-input-label class="text-blue-700 font-bold" :value="__('Harga (%)')" />
                                            <x-text-input type="number" name="weight_harga" class="mt-2 w-full border-none rounded-xl text-center font-black text-xl" value="40" required />
                                        </div>
                                        <div class="p-5 bg-purple-50/50 rounded-3xl border border-purple-100 text-center">
                                            <x-input-label class="text-purple-700 font-bold" :value="__('Teknis (%)')" />
                                            <x-text-input type="number" name="weight_teknis" class="mt-2 w-full border-none rounded-xl text-center font-black text-xl" value="40" required />
                                        </div>
                                        <div class="p-5 bg-emerald-50/50 rounded-3xl border border-emerald-100 text-center">
                                            <x-input-label class="text-emerald-700 font-bold" :value="__('Integritas (%)')" />
                                            <x-text-input type="number" name="weight_integritas" class="mt-2 w-full border-none rounded-xl text-center font-black text-xl" value="20" required />
                                        </div>
                                    </div>

                                    <x-primary-button class="w-full justify-center py-4 bg-slate-900 hover:bg-black rounded-2xl transition-all shadow-xl">
                                        {{ __('Publikasikan Paket Tender & Dokumen') }}
                                    </x-primary-button>
                                </form>
                            </div>
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
                        </div>
                    @endrole

                    {{-- ========================================== --}}
                    {{-- SEKSI VENDOR (PBI-03, 06, 07, 10)          --}}
                    {{-- ========================================== --}}
                    @role('vendor')
                        <div class="mb-10 grid grid-cols-1 lg:grid-cols-3 gap-8">
                            {{-- DIGITAL CERTIFICATE --}}
                            <div class="lg:col-span-2 bg-gradient-to-br from-slate-900 via-indigo-950 to-black rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                                <div class="relative z-10 h-full flex flex-col justify-between">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-[10px] font-bold text-indigo-400 tracking-[0.3em] uppercase opacity-80 italic underline">Verified Strategic Partner</p>
                                            <h2 class="text-4xl font-black mt-2 uppercase tracking-tighter leading-none group-hover:text-indigo-300 transition-colors">{{ Auth::user()->name }}</h2>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[9px] bg-white/5 px-3 py-1.5 rounded-full border border-white/10 font-mono tracking-tighter">UID: {{ Auth::user()->id }}-2026</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-16 flex items-center bg-white/5 p-6 rounded-3xl border border-white/5 backdrop-blur-sm">
                                        <div class="h-12 w-12 bg-green-500 rounded-2xl flex items-center justify-center shadow-lg transform rotate-3">
                                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <div class="ml-6">
                                            <p class="text-green-400 font-black text-xl tracking-tight uppercase leading-tight">Identity Status: {{ Auth::user()->status ?? 'ACTIVE' }}</p>
                                            <p class="text-[10px] text-indigo-200/60 font-medium">Data Anda dilindungi enkripsi Sealed Bidding.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute -bottom-20 -right-20 h-64 w-64 bg-indigo-600/10 rounded-full blur-[80px]"></div>
                            </div>

                            {{-- SYSTEM METRICS --}}
                            <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-100 flex flex-col justify-center relative overflow-hidden">
                                <div class="relative z-10">
                                    <h3 class="font-black text-xl mb-4 flex items-center italic">
                                        <span class="mr-2 not-italic">🎯</span> Quick Metrics
                                    </h3>
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center border-b border-white/10 pb-2">
                                            <span class="text-xs font-medium">Bidding Encrypted</span>
                                            <span class="text-xs font-black">ACTIVE</span>
                                        </div>
                                        <div class="flex justify-between items-center border-b border-white/10 pb-2">
                                            <span class="text-xs font-medium">Pagu Validation</span>
                                            <span class="text-xs font-black">ENABLED</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute -top-10 -left-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                            </div>
                        </div>

                        {{-- FEATURE: SEALED BIDDING (PBI-10) --}}
                        <div class="bg-gray-900 p-8 rounded-[2.5rem] text-white shadow-2xl border border-white/5 relative overflow-hidden mb-12">
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-8">
                                    <h3 class="text-2xl font-black flex items-center">
                                        <span class="mr-3 bg-white/10 p-2 rounded-xl text-indigo-400">🔐</span> 
                                        {{ __('Sealed Bidding Portal') }}
                                    </h3>
                                    <span class="px-4 py-1 bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 rounded-full text-[10px] font-black uppercase tracking-widest">Enkripsi Aktif</span>
                                </div>

                                <form action="{{ route('bid.store') }}" method="POST" class="space-y-6">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div>
                                            <x-input-label class="text-gray-400 mb-2" :value="__('Pilih Paket Tender Aktif')" />
                                            <select name="tender_config_id" class="w-full bg-white/5 border-white/10 rounded-2xl text-white py-4 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
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
                                                <input type="number" name="offered_price" class="w-full bg-white/5 border-white/10 rounded-2xl text-white py-4 pl-14 focus:ring-indigo-500 focus:border-indigo-500" placeholder="0" required />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white/5 p-4 rounded-2xl border border-white/5">
                                        <p class="text-[10px] text-gray-400 leading-relaxed italic">
                                            *PBI-10 Security Logic: Harga Anda akan langsung di-hash menggunakan AES-256-CBC Encryption sebelum disimpan ke database. Penawaran hanya dapat dibuka oleh sistem pada saat sesi evaluasi teknis.
                                        </p>
                                    </div>

                                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-600/20 transition-all active:scale-[0.98]">
                                        KIRIM & KUNCI PENAWARAN
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- FORM PENGADAAN (PBI-06 & 07) --}}
                        <div class="mt-8 bg-gray-50 p-10 rounded-[3rem] border-2 border-dashed border-gray-200">
                            <h3 class="text-2xl font-black text-gray-900 flex items-center mb-8">
                                <span class="mr-3 bg-white p-2 rounded-2xl shadow-sm text-indigo-600">📦</span> {{ __('Permintaan Pengadaan Cerdas (Smart Check)') }}
                            </h3>
                            <form action="{{ route('procurement.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <div class="max-w-md mb-8 p-6 bg-white rounded-3xl shadow-sm">
                                    <x-input-label for="budget_id" :value="__('Gunakan Plafon Pagu (PBI-07)')" />
                                    <select name="budget_id" id="budget_id" class="mt-3 block w-full border-gray-200 rounded-xl focus:ring-indigo-500" required>
                                        <option value="">-- Pilih Pagu --</option>
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