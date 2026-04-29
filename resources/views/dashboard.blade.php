<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Utama INAPROC+') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[3rem] border border-gray-100">
                <div class="p-8 md:p-12 text-gray-900">
                    
                    {{-- ========================================== --}}
                    {{-- SEKSI ADMINISTRATOR (PBI 04, 08, 09, 11, 12) --}}
                    {{-- ========================================== --}}
                    @hasanyrole('admin|auditor')
                        <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h1 class="text-4xl font-black text-gray-900 tracking-tighter">Control Center</h1>
                                <p class="text-gray-500 mt-1 font-medium italic">Monitoring real-time pengadaan dan objektifitas seleksi vendor.</p>
                            </div>
                            <div>
                                <span class="bg-indigo-600 text-white px-6 py-3 rounded-2xl text-xs font-black shadow-xl shadow-indigo-200 uppercase tracking-widest border-b-4 border-indigo-800">
                                    System Controller Active
                                </span>
                            </div>
                        </div>

                        {{-- TENDER PUBLICATION (PBI 08 & 09) --}}
                        <div class="bg-white p-8 rounded-[2.5rem] border-2 border-indigo-50 shadow-sm relative overflow-hidden mb-12">
                            <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center">
                                <span class="mr-3 bg-indigo-50 p-2 rounded-xl text-indigo-600 shadow-inner">🏗️</span> 
                                {{ __('Penerbitan Tender & Konfigurasi Bobot') }}
                            </h3>

                            <form action="{{ route('tender-config.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                                @csrf
                                <fieldset @role('auditor') disabled @endrole>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8" x-data="{
                                        selectedProcurement: '',
                                        description: '',
                                        updateDetails(event) {
                                            const option = event.target.options[event.target.selectedIndex];
                                            this.description = option.dataset.desc || '';
                                        }
                                    }">
                                        <div class="flex flex-col gap-4">
                                            <div>
                                                <x-input-label for="judul_tender" :value="__('Judul Paket Pengadaan')" class="font-bold ml-1" />
                                                <select name="judul_tender" class="mt-2 block w-full bg-gray-50 border-none rounded-2xl py-4 text-sm focus:ring-indigo-500" required @change="updateDetails($event)">
                                                    <option value="" data-desc="">-- Pilih Pengadaan yang Disetujui --</option>
                                                    @foreach($approvedProcurements ?? [] as $procurement)
                                                        <option value="{{ $procurement->id }}" data-desc="{{ $procurement->description }}">
                                                            {{ $procurement->item_name }} (Sisa Pagu: Rp {{ number_format($procurement->budget->sisa_pagu ?? 0, 0, ',', '.') }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if(count($approvedProcurements ?? []) == 0)
                                                    <p class="text-xs text-red-500 mt-2 ml-1">Tidak ada pengadaan dengan status 'approved' yang belum dibuat tender.</p>
                                                @endif
                                            </div>
                                            <div>
                                                <x-input-label class="font-bold ml-1 text-gray-500" :value="__('Deskripsi Pengadaan (Autofill)')" />
                                                <textarea readonly x-model="description" class="mt-2 block w-full bg-gray-100 border-none rounded-2xl py-3 text-sm text-gray-600 focus:ring-0 resize-none h-24" placeholder="Deskripsi akan terisi otomatis..."></textarea>
                                            </div>
                                        </div>
                                        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 border-dashed flex flex-col justify-center">
                                            <x-input-label class="text-amber-700 font-bold" :value="__('Dokumen TOR/KAK (PBI-09)')" />
                                            <input type="file" name="tor_file" class="mt-4 block w-full text-xs text-amber-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-amber-600 file:text-white hover:file:bg-amber-700 transition" required>
                                            <p class="text-[10px] text-amber-600 mt-3 font-medium">Unggah format .pdf atau .docx (Max 5MB).</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center mt-8">
                                        <div class="p-6 bg-blue-50/50 rounded-[2rem] border border-blue-100 group hover:bg-blue-50 transition-colors">
                                            <x-input-label class="text-blue-700 font-black uppercase text-[10px] tracking-widest" :value="__('Bobot Harga (%)')" />
                                            <x-text-input type="number" name="weight_harga" class="mt-3 w-full border-none rounded-xl text-center font-black text-2xl bg-transparent text-blue-900" value="40" required />
                                        </div>
                                        <div class="p-6 bg-purple-50/50 rounded-[2rem] border border-purple-100 group hover:bg-purple-50 transition-colors">
                                            <x-input-label class="text-purple-700 font-black uppercase text-[10px] tracking-widest" :value="__('Bobot Teknis (%)')" />
                                            <x-text-input type="number" name="weight_teknis" class="mt-3 w-full border-none rounded-xl text-center font-black text-2xl bg-transparent text-purple-900" value="40" required />
                                        </div>
                                        <div class="p-6 bg-emerald-50/50 rounded-[2rem] border border-emerald-100 group hover:bg-emerald-50 transition-colors">
                                            <x-input-label class="text-emerald-700 font-black uppercase text-[10px] tracking-widest" :value="__('Bobot Integritas (%)')" />
                                            <x-text-input type="number" name="weight_integritas" class="mt-3 w-full border-none rounded-xl text-center font-black text-2xl bg-transparent text-emerald-900" value="20" required />
                                        </div>
                                    </div>
                                </fieldset>

                                @role('admin')
                                <x-primary-button class="w-full justify-center py-5 bg-slate-900 rounded-[1.5rem] shadow-2xl hover:scale-[1.01] transition-transform font-black tracking-widest mt-8">
                                    {{ __('PUBLIKASIKAN PAKET TENDER') }}
                                </x-primary-button>
                                @endrole
                            </form>
                        </div>

                        {{-- MATRIKS KOMPARASI & AUTO-SCORING (PBI-11) --}}
                        <div class="mt-16 bg-white p-8 rounded-[3rem] border border-gray-100 shadow-2xl">
                            <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
                                <h3 class="text-2xl font-black text-gray-900 flex items-center">
                                    <span class="mr-4 bg-indigo-600 text-white p-3 rounded-2xl shadow-lg shadow-indigo-100">📊</span> 
                                    {{ __('Auto-Scoring Calculation Engine') }}
                                </h3>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-indigo-100 text-indigo-700 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-indigo-200">DSS Algorithm 1.0</span>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-gray-50/50 text-gray-400 font-black uppercase text-[10px] tracking-[0.2em]">
                                        <tr>
                                            <th class="px-8 py-5">Nama Vendor</th>
                                            <th class="px-6 py-5 text-center">Price Score</th>
                                            <th class="px-6 py-5 text-center">Tech Score</th>
                                            <th class="px-6 py-5 text-center">Integ Score</th>
                                            <th class="px-8 py-5 text-right text-indigo-600">Weighted Total</th>
                                            <th class="px-8 py-5 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse(\App\Models\Bid::with('user')->get() as $bid)
                                        <tr class="hover:bg-indigo-50/20 transition-colors group">
                                            <td class="px-8 py-6">
                                                <div class="font-black text-gray-800 text-base">{{ $bid->user->name }}</div>
                                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Vendor ID: #{{ $bid->user_id }}</div>
                                            </td>
                                            <td class="px-6 py-6 text-center font-bold text-gray-600">{{ number_format($bid->score_harga, 2) ?? 'N/A' }}</td>
                                            <td class="px-6 py-6 text-center font-bold text-gray-600">{{ number_format($bid->score_teknis, 2) ?? 'N/A' }}</td>
                                            <td class="px-6 py-6 text-center font-bold text-gray-600">{{ number_format($bid->score_integritas, 2) ?? 'N/A' }}</td>
                                            <td class="px-8 py-6 text-right">
                                                <span class="font-black text-xl text-indigo-600 tracking-tighter">{{ number_format($bid->final_score, 2) ?? '0.00' }}</span>
                                            </td>
                                            <td class="px-8 py-6 text-right">
                                                <form action="{{ route('bid.calculate', $bid->id) }}" method="POST">
                                                    @csrf
                                                    @role('admin')
                                                    <button type="submit" class="bg-white border-2 border-indigo-600 text-indigo-600 px-5 py-2.5 rounded-xl text-[10px] font-black hover:bg-indigo-600 hover:text-white transition-all shadow-sm active:scale-95">
                                                        RE-CALCULATE
                                                    </button>
                                                    @endrole
                                                    @role('auditor')
                                                    <span class="text-xs text-gray-400 italic">Read-only</span>
                                                    @endrole
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr><td colspan="6" class="py-16 text-center text-gray-400 font-medium italic">Belum ada penawaran (bids) yang masuk untuk dihitung.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- COMPETITIVE VENDOR MATRIX (PBI-12) --}}
                        <div class="mt-16 bg-slate-900 p-10 rounded-[4rem] text-white shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] relative overflow-hidden">
                            <div class="relative z-10">
                                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                                    <div>
                                        <h3 class="text-3xl font-black tracking-tighter flex items-center">
                                            <span class="mr-4 text-4xl">🏆</span> 
                                            Competitive Vendor Matrix
                                        </h3>
                                        <p class="text-slate-400 mt-2 font-medium italic">Visualisasi peringkat vendor berdasarkan data objektif terintegrasi.</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                                            <select name="tender_id" onchange="this.form.submit()" class="bg-white/10 border border-white/20 text-white text-xs rounded-xl px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="" class="text-black">-- Semua Tender --</option>
                                                @foreach($allTenders ?? \App\Models\Tender::all() as $t)
                                                    <option value="{{ $t->id }}" class="text-black" {{ ($filterTenderId ?? '') == $t->id ? 'selected' : '' }}>{{ $t->title }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                        <div class="hidden md:block">
                                            <span class="px-5 py-2 bg-green-500/20 text-green-400 border border-green-500/30 rounded-full text-[10px] font-black uppercase tracking-[0.2em] animate-pulse">Live Ranking</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-6">
                                    @forelse($competitiveMatrix ?? \App\Models\Bid::with('user')->orderBy('final_score', 'desc')->get() as $index => $competitor)
                                        <div class="bg-white/5 border border-white/10 p-8 rounded-[2.5rem] flex flex-wrap items-center justify-between transition-all hover:bg-white/10 hover:border-indigo-500/50 group">
                                            <div class="flex items-center space-x-8">
                                                <div class="h-16 w-16 {{ $index == 0 ? 'bg-gradient-to-br from-yellow-400 to-orange-500' : ($index == 1 ? 'bg-slate-400' : ($index == 2 ? 'bg-orange-700' : 'bg-slate-700')) }} rounded-[1.5rem] flex items-center justify-center text-white font-black text-3xl shadow-2xl group-hover:rotate-6 transition-transform">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div>
                                                    <h4 class="font-black text-white uppercase text-xl tracking-tight">{{ $competitor->user->name }}</h4>
                                                    <div class="flex items-center mt-2 space-x-3">
                                                        <span class="text-[9px] bg-white/10 text-slate-300 px-3 py-1 rounded-full font-bold uppercase">UID: #{{ $competitor->user_id }}</span>
                                                        @if($index == 0)
                                                            <span class="text-[9px] bg-green-500 text-white px-3 py-1 rounded-full font-black uppercase tracking-widest italic shadow-lg shadow-green-500/20">Top Recommendation</span>
                                                        @endif
                                                        @role('auditor')
                                                        <a href="{{ route('auditor.surveys.create', $competitor->user_id) }}" class="text-[9px] bg-amber-500 text-amber-950 px-3 py-1 rounded-full font-black uppercase tracking-widest shadow-lg hover:bg-amber-400 transition">Input Survey</a>
                                                        @if($competitor->status == 'winner')
                                                        <a href="{{ route('procurement.spk', $competitor->tender->procurement_request_id ?? 0) }}" class="text-[9px] bg-indigo-600 text-white px-3 py-1 rounded-full font-black uppercase tracking-widest shadow-lg hover:bg-indigo-700 transition" target="_blank">Cetak SPK</a>
                                                        @endif
                                                        @endrole
                                                        <span class="text-[9px] bg-indigo-500/20 text-indigo-300 px-3 py-1 rounded-full font-bold uppercase border border-indigo-500/30">
                                                            Infra: {{ $competitor->user->surveyReport->infrastructure_score ?? 'N/A' }} | K.Kantor: {{ $competitor->user->surveyReport->office_condition ?? 'N/A' }}
                                                        </span>
                                                        @role('auditor')
                                                        <span class="text-[9px] bg-emerald-500/20 text-emerald-300 px-3 py-1 rounded-full font-bold uppercase border border-emerald-500/30">
                                                            Penawaran: Rp {{ number_format((float) $competitor->getDecryptedPrice(), 0, ',', '.') }}
                                                        </span>
                                                        @endrole
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hidden xl:flex items-center space-x-16">
                                                <div class="w-40">
                                                    <div class="flex justify-between mb-2">
                                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Price</span>
                                                        <span class="text-[10px] font-black text-blue-400">{{ number_format($competitor->score_harga, 1) }}</span>
                                                    </div>
                                                    <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden p-[1px]">
                                                        <div class="h-full bg-blue-500 rounded-full shadow-[0_0_10px_rgba(59,130,246,0.5)]" style="width: {{ $competitor->score_harga }}%"></div>
                                                    </div>
                                                </div>
                                                <div class="w-40">
                                                    <div class="flex justify-between mb-2">
                                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Technical</span>
                                                        <span class="text-[10px] font-black text-purple-400">{{ number_format($competitor->score_teknis, 1) }}</span>
                                                    </div>
                                                    <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden p-[1px]">
                                                        <div class="h-full bg-purple-500 rounded-full shadow-[0_0_10px_rgba(168,85,247,0.5)]" style="width: {{ $competitor->score_teknis }}%"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right min-w-[160px] flex flex-col items-end justify-center space-y-3">
                                                <div>
                                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">Final Index</p>
                                                    <p class="text-5xl font-black text-indigo-400 tracking-tighter group-hover:scale-110 transition-transform origin-right">{{ number_format($competitor->final_score, 2) }}</p>
                                                </div>
                                                @role('auditor')
                                                    @if($competitor->status == 'winner')
                                                        <span class="text-green-400 font-black text-[10px] uppercase tracking-widest flex items-center bg-green-500/10 px-3 py-1.5 rounded-xl border border-green-500/20"><span class="mr-2 text-sm">🏆</span> WINNER</span>
                                                    @elseif($index == 0 && ($competitor->tender->status ?? 'closed') == 'open')
                                                        <form action="{{ route('bid.setWinner', $competitor->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-[9px] font-black uppercase px-4 py-2.5 rounded-xl shadow-lg transition-all tracking-widest border border-indigo-400/50">Tetapkan Pemenang</button>
                                                        </form>
                                                    @endif
                                                @endrole
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-16 text-center border-2 border-dashed border-slate-700 rounded-[3rem]">
                                            <p class="text-slate-500 font-black uppercase tracking-widest text-xs italic">Waiting for Calculation Data...</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px]"></div>
                            <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-600/10 rounded-full blur-[100px]"></div>
                        </div>

                        {{-- SYSTEM ACTIVITY LOG (PBI-13 Audit Trail) --}}
                        <div class="mt-16 bg-white p-8 rounded-[3rem] border border-gray-100 shadow-xl">
                            <h3 class="text-2xl font-black text-gray-900 flex items-center mb-8">
                                <span class="mr-4 bg-gray-100 p-3 rounded-2xl shadow-sm text-gray-600">📝</span> 
                                {{ __('System Activity Log') }}
                            </h3>
                            <div class="space-y-4">
                                @forelse($activityLogs ?? [] as $log)
                                    <div class="p-6 bg-gray-50 border border-gray-100 rounded-2xl hover:bg-gray-100 transition-colors">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-xs font-black text-indigo-600 uppercase tracking-widest">{{ $log->action }}</p>
                                                <p class="font-bold text-gray-800 mt-1">{{ $log->description }}</p>
                                                <p class="text-[10px] text-gray-500 mt-2 font-medium">Oleh: <span class="text-gray-700 font-bold">{{ $log->user->name ?? 'System' }}</span></p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-[10px] bg-white border border-gray-200 text-gray-500 px-3 py-1 rounded-full font-bold shadow-sm">
                                                    {{ $log->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-8 text-center border-2 border-dashed border-gray-200 rounded-2xl">
                                        <p class="text-gray-400 font-bold italic text-sm">Belum ada log aktivitas tercatat.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endhasanyrole

                    {{-- ========================================== --}}
                    {{-- SEKSI VENDOR (PBI 03, 06, 07, 10)          --}}
                    {{-- ========================================== --}}
                    @role('vendor')
                        {{-- UI VENDOR TETAP SESUAI KODE SEBELUMNYA (KARENA SUDAH FINAL) --}}
                        <div class="mb-10 grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <div class="lg:col-span-2 bg-gradient-to-br from-slate-900 via-indigo-950 to-black rounded-[3rem] p-12 text-white shadow-2xl relative overflow-hidden group">
                                <div class="relative z-10 h-full flex flex-col justify-between">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-[10px] font-bold text-indigo-400 tracking-[0.3em] uppercase opacity-80 italic underline">Verified Strategic Partner</p>
                                            <h2 class="text-5xl font-black mt-3 uppercase tracking-tighter leading-none group-hover:text-indigo-300 transition-colors">{{ Auth::user()->name }}</h2>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[9px] bg-white/5 px-4 py-2 rounded-full border border-white/10 font-mono">UID: {{ Auth::user()->id }}-2026</span>
                                        </div>
                                    </div>
                                    <div class="mt-20 flex items-center bg-white/5 p-8 rounded-[2rem] border border-white/5 backdrop-blur-md">
                                        <div class="h-12 w-12 bg-green-500 rounded-2xl flex items-center justify-center shadow-lg transform rotate-3 mr-6">
                                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-green-400 font-black text-2xl uppercase tracking-tighter">Status: {{ Auth::user()->status ?? 'ACTIVE' }}</p>
                                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-widest mt-1">Ready for Sealed Bidding Participation</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute -bottom-20 -right-20 h-80 w-84 bg-indigo-600/10 rounded-full blur-[100px]"></div>
                            </div>

                            <div class="bg-indigo-600 rounded-[3rem] p-10 text-white shadow-2xl flex flex-col justify-center relative overflow-hidden">
                                <div class="relative z-10">
                                    <h3 class="font-black text-2xl mb-6 italic tracking-tighter">🎯 Quick Metrics</h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center border-b border-white/10 pb-3">
                                            <span class="text-xs font-bold uppercase text-indigo-200">Bidding Encryption</span>
                                            <span class="text-[10px] font-black uppercase bg-white/10 px-3 py-1 rounded-full">Active</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-bold uppercase text-indigo-200">Pagu Validation</span>
                                            <span class="text-[10px] font-black uppercase bg-white/10 px-3 py-1 rounded-full">Enabled</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                            </div>
                        </div>

                        {{-- SEALED BIDDING PORTAL (PBI-10) --}}
                        <div class="bg-gray-900 p-10 rounded-[3rem] text-white shadow-2xl border border-white/5 relative overflow-hidden mb-12">
                            <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4 relative z-10">
                                <h3 class="text-3xl font-black flex items-center tracking-tighter">
                                    <span class="mr-4 bg-white/10 p-3 rounded-2xl text-indigo-400 shadow-inner">🔐</span> 
                                    {{ __('Sealed Bidding Portal') }}
                                </h3>
                                <div>
                                    <a href="{{ route('vendor.bids') }}" class="inline-block bg-white/10 hover:bg-white/20 border border-white/20 text-white px-6 py-3 rounded-2xl text-xs font-black shadow-lg shadow-black/20 uppercase tracking-widest transition-all">
                                        Lihat Riwayat & Edit Penawaran
                                    </a>
                                </div>
                            </div>

                            <form action="{{ route('bid.store') }}" method="POST" class="space-y-8">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div>
                                        <x-input-label class="text-slate-400 mb-3 ml-1 font-bold uppercase text-[10px] tracking-widest" :value="__('Pilih Paket Tender Aktif')" />
                                        @php
                                            $availableTenders = \App\Models\Tender::where('status', 'open')
                                                ->whereDoesntHave('bids', function($q) {
                                                    $q->where('user_id', auth()->id());
                                                })->get();
                                        @endphp

                                        <select name="tender_id" class="w-full bg-white/5 border-white/10 rounded-2xl text-white py-5 px-6 focus:ring-2 focus:ring-indigo-500 transition-all appearance-none" required @if($availableTenders->isEmpty()) disabled @endif>
                                            @if($availableTenders->isEmpty())
                                                <option value="" class="text-black">-- Semua tender aktif sudah Anda ajukan penawaran --</option>
                                            @else
                                                <option value="" class="text-black">-- Select Tender --</option>
                                                @foreach($availableTenders as $tender)
                                                    <option value="{{ $tender->id }}" class="text-black">{{ $tender->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label class="text-slate-400 mb-3 ml-1 font-bold uppercase text-[10px] tracking-widest" :value="__('Nilai Penawaran (Price)')" />
                                        <div class="relative">
                                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-indigo-400 font-black text-xl">Rp</span>
                                            <input type="number" name="offered_price" class="w-full bg-white/5 border-white/10 rounded-2xl text-white py-5 pl-16 pr-6 focus:ring-2 focus:ring-indigo-500 text-xl font-black" placeholder="0" required />
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-5 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-[0_20px_50px_rgba(79,70,229,0.3)] transition-all active:scale-[0.99] uppercase tracking-widest disabled:opacity-50 disabled:cursor-not-allowed" @if($availableTenders->isEmpty()) disabled @endif>
                                    KIRIM PENAWARAN & ENKRIPSI DATA
                                </button>
                                <p class="text-center text-[10px] text-slate-500 font-medium italic">Semua data harga akan dikunci menggunakan AES-256-CBC Encryption Engine.</p>
                            </form>
                        </div>

                        {{-- SMART PROCUREMENT FORM (PBI 06 & 07) --}}
                        <div class="bg-gray-50 p-12 rounded-[4rem] border-2 border-dashed border-gray-200">
                            <h3 class="text-2xl font-black text-gray-900 flex items-center mb-10 tracking-tight">
                                <span class="mr-4 bg-white p-3 rounded-2xl shadow-sm text-indigo-600">📦</span> {{ __('Smart Procurement Request') }}
                            </h3>
                            <form action="{{ route('procurement.store') }}" method="POST" class="space-y-8">
                                @csrf
                                <div class="max-w-md mb-10 p-8 bg-white rounded-[2rem] shadow-xl shadow-gray-100 border border-gray-50">
                                    <x-input-label for="budget_id" :value="__('Gunakan Plafon Pagu (Budget Guard)')" class="font-bold mb-3" />
                                    <select name="budget_id" id="budget_id" class="mt-1 block w-full border-gray-100 rounded-xl py-4 focus:ring-2 focus:ring-indigo-500 bg-gray-50 font-bold" required>
                                        @foreach(\App\Models\Budget::all() as $budget)
                                            <option value="{{ $budget->id }}">{{ $budget->nama_pagu }} (Sisa: Rp {{ number_format($budget->sisa_pagu) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <x-text-input name="item_name" placeholder="Item / Jasa" required class="rounded-2xl border-none shadow-lg py-5 px-6" />
                                    <x-text-input name="quantity" type="number" placeholder="Kuantitas" required class="rounded-2xl border-none shadow-lg py-5 px-6" />
                                    <x-text-input name="price" type="number" placeholder="Harga Satuan" required class="rounded-2xl border-none shadow-lg py-5 px-6" />
                                </div>
                                <div class="flex justify-end">
                                    <x-primary-button class="bg-indigo-600 py-5 px-16 rounded-2xl text-base font-black shadow-xl shadow-indigo-100">SUBMIT REQUEST</x-primary-button>
                                </div>
                            </form>
                        </div>
                    @endrole

                </div>
            </div>
        </div>
    </div>
</x-app-layout>