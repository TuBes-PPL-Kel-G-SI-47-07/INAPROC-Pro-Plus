<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Dashboard Utama INAPROC+') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- NOTIFIKASI SISTEM --}}
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 rounded-lg shadow-sm" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            {{-- ERROR HANDLING --}}
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow-sm" role="alert">
                    <p class="font-bold">Terjadi Kesalahan:</p>
                    <ul class="text-sm list-disc ml-5 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100">
                <div class="p-8 md:p-12 text-slate-900">
                    
                    {{-- ========================================== --}}
                    {{-- SEKSI ADMINISTRATOR (PBI 04, 08, 09, 11, 12) --}}
                    {{-- ========================================== --}}
                    @hasanyrole('admin|auditor')
                        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Control Center</h1>
                                <p class="text-slate-500 mt-1 text-sm">Monitoring real-time pengadaan dan objektifitas seleksi vendor.</p>
                            </div>
                            <div>
                                <span class="bg-blue-100 text-blue-700 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider border border-blue-200">
                                    System Controller Active
                                </span>
                            </div>
                        </div>

                        {{-- TENDER PUBLICATION (PBI 08 & 09) --}}
                        <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden mb-12">
                            <h3 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-4">
                                {{ __('Penerbitan Tender & Konfigurasi Bobot') }}
                            </h3>

                            <form action="{{ route('tender-config.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <fieldset @role('auditor') disabled @endrole class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{
                                        selectedProcurement: '',
                                        description: '',
                                        updateDetails(event) {
                                            const option = event.target.options[event.target.selectedIndex];
                                            this.description = option.dataset.desc || '';
                                        }
                                    }">
                                        <div class="flex flex-col gap-4">
                                            <div>
                                                <x-input-label for="judul_tender" :value="__('Judul Paket Pengadaan')" class="font-medium text-slate-700 mb-2" />
                                                <select name="judul_tender" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all" required @change="updateDetails($event)">
                                                    <option value="" data-desc="">-- Pilih Pengadaan yang Disetujui --</option>
                                                    @foreach($approvedProcurements ?? [] as $procurement)
                                                        <option value="{{ $procurement->id }}" data-desc="{{ $procurement->description }}">
                                                            {{ $procurement->item_name }} (Sisa Pagu: Rp {{ number_format($procurement->budget->sisa_pagu ?? 0, 0, ',', '.') }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if(count($approvedProcurements ?? []) == 0)
                                                    <p class="text-xs text-amber-600 mt-2">Tidak ada pengadaan dengan status 'approved' yang belum dibuat tender.</p>
                                                @endif
                                            </div>
                                            <div>
                                                <x-input-label class="font-medium text-slate-700 mb-2" :value="__('Deskripsi Pengadaan (Autofill)')" />
                                                <textarea readonly x-model="description" class="bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-lg block w-full p-2.5 focus:ring-0 focus:border-slate-200 resize-none h-24" placeholder="Deskripsi akan terisi otomatis..."></textarea>
                                            </div>
                                        </div>
                                        <div>
                                            <x-input-label class="font-medium text-slate-700 mb-2" :value="__('Dokumen TOR/KAK (PBI-09)')" />
                                            <div class="w-full h-[8.5rem] p-4 bg-slate-50 rounded-lg border-2 border-slate-200 border-dashed flex flex-col justify-center items-center text-center hover:bg-slate-100 transition-colors">
                                                <input type="file" name="tor_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer" required>
                                                <p class="text-xs text-slate-500 mt-2">Format .pdf atau .docx (Max 5MB)</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-slate-100">
                                        <div>
                                            <x-input-label class="font-medium text-slate-700 mb-2" :value="__('Bobot Harga (%)')" />
                                            <x-text-input type="number" name="weight_harga" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all font-semibold" value="40" required />
                                        </div>
                                        <div>
                                            <x-input-label class="font-medium text-slate-700 mb-2" :value="__('Bobot Teknis (%)')" />
                                            <x-text-input type="number" name="weight_teknis" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all font-semibold" value="40" required />
                                        </div>
                                        <div>
                                            <x-input-label class="font-medium text-slate-700 mb-2" :value="__('Bobot Integritas (%)')" />
                                            <x-text-input type="number" name="weight_integritas" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all font-semibold" value="20" required />
                                        </div>
                                    </div>
                                </fieldset>

                                @role('admin')
                                <div class="mt-8 flex justify-end">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all focus:ring-4 focus:ring-blue-300">
                                        {{ __('Publikasikan Paket Tender') }}
                                    </button>
                                </div>
                                @endrole
                            </form>
                        </div>

                        {{-- MATRIKS KOMPARASI & AUTO-SCORING (PBI-11) --}}
                        <div class="mt-12 bg-white shadow-sm rounded-xl overflow-hidden border border-slate-100">
                            <div class="px-6 py-5 border-b border-slate-200 bg-white flex justify-between items-center">
                                <h3 class="text-lg font-bold text-slate-800">
                                    {{ __('Auto-Scoring Calculation Engine') }}
                                </h3>
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-medium border border-slate-200">DSS Algorithm 1.0</span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-slate-50 border-b border-slate-200">
                                        <tr>
                                            <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider">Nama Vendor</th>
                                            <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider text-center">Price Score</th>
                                            <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider text-center">Tech Score</th>
                                            <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider text-center">Integ Score</th>
                                            <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider text-right">Weighted Total</th>
                                            <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse(\App\Models\Bid::with('user')->get() as $bid)
                                        <tr class="hover:bg-slate-50 transition-colors border-b border-slate-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-semibold text-slate-800">{{ $bid->user->name }}</div>
                                                <div class="text-xs text-slate-500 font-mono mt-1">UID: #{{ $bid->user_id }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-600">{{ number_format($bid->score_harga, 2) ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-600">{{ number_format($bid->score_teknis, 2) ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-600">{{ number_format($bid->score_integritas, 2) ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span class="font-bold text-lg text-blue-600">{{ number_format($bid->final_score, 2) ?? '0.00' }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <form action="{{ route('bid.calculate', $bid->id) }}" method="POST">
                                                    @csrf
                                                    @role('admin')
                                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-sm">
                                                        Re-calculate
                                                    </button>
                                                    @endrole
                                                    @role('auditor')
                                                    <span class="text-xs text-slate-400 italic">Read-only</span>
                                                    @endrole
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500 text-sm italic">Belum ada penawaran (bids) yang masuk untuk dihitung.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- COMPETITIVE VENDOR MATRIX (PBI-12) --}}
                        <div class="mt-12 bg-white shadow-sm rounded-xl overflow-hidden border border-slate-100">
                            <div class="px-6 py-5 border-b border-slate-200 bg-white flex justify-between items-center">
                                <h3 class="text-lg font-bold text-slate-800">
                                    {{ __('Competitive Vendor Matrix') }}
                                </h3>
                                <div class="flex items-center gap-4">
                                    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                                        <select name="tender_id" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 text-slate-700 text-xs rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                            <option value="">-- Semua Tender --</option>
                                            @foreach($allTenders ?? \App\Models\Tender::all() as $t)
                                                <option value="{{ $t->id }}" {{ ($filterTenderId ?? '') == $t->id ? 'selected' : '' }}>{{ $t->title }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-700">Live Ranking</span>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-slate-50 border-b border-slate-200">
                                        <tr>
                                            <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider">Rank</th>
                                            <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider">Vendor Info</th>
                                            <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider">Status</th>
                                            <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider text-right">Final Score</th>
                                            <th class="px-6 py-4 text-xs uppercase font-semibold text-slate-500 tracking-wider text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($competitiveMatrix ?? \App\Models\Bid::with('user')->orderBy('final_score', 'desc')->get() as $index => $competitor)
                                            <tr class="hover:bg-slate-50 transition-colors border-b border-slate-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold {{ $index == 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }}">
                                                        {{ $index + 1 }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="font-semibold text-slate-800">{{ $competitor->user->name }}</div>
                                                    <div class="text-xs text-slate-500 font-mono mt-1">UID: #{{ $competitor->user_id }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($competitor->status == 'winner')
                                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-700">Winner</span>
                                                    @elseif($index == 0)
                                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-700">Top Candidate</span>
                                                    @else
                                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-100 text-slate-700">Participant</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    <span class="font-bold text-lg text-slate-800">{{ number_format($competitor->final_score, 2) }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        @role('auditor')
                                                            @if($competitor->status == 'winner')
                                                                <a href="{{ route('procurement.spk', $competitor->tender->procurement_request_id ?? 0) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent rounded-lg text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-all" target="_blank">
                                                                    Cetak SPK
                                                                </a>
                                                            @elseif($index == 0 && ($competitor->tender->status ?? 'closed') == 'open')
                                                                <form action="{{ route('bid.setWinner', $competitor->id) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent rounded-lg text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-all">
                                                                        Set Winner
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            <a href="{{ route('auditor.surveys.create', $competitor->user_id) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 transition-all shadow-sm">
                                                                Input Survey
                                                            </a>
                                                        @endrole
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm italic">
                                                    Waiting for Calculation Data...
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- IMMUTABLE AUDIT TRAIL (PBI-19) --}}
                        <div class="mt-12 bg-white shadow-sm rounded-xl overflow-hidden border border-slate-100">
                            <div class="px-6 py-5 border-b border-slate-200 bg-white">
                                <h3 class="text-lg font-bold text-slate-800 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    {{ __('Immutable Audit Trail') }}
                                </h3>
                            </div>
                            <div class="p-8">
                                <div class="relative border-l-2 border-slate-100 ml-3 space-y-8">
                                    @forelse($activityLogs ?? [] as $log)
                                        <div class="relative pl-8 group">
                                            <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-white border-2 border-slate-300 group-hover:border-emerald-500 transition-colors"></div>
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-widest mb-2">
                                                        {{ $log->action }}
                                                    </span>
                                                    <p class="font-medium text-slate-800 text-sm">{{ $log->description }}</p>
                                                    <div class="flex flex-wrap items-center mt-3 gap-3">
                                                        <p class="text-xs text-slate-500">Oleh: <span class="font-semibold text-slate-700">{{ $log->user->name ?? 'System' }}</span></p>
                                                        <span class="text-slate-300 hidden sm:inline">•</span>
                                                        <p class="font-mono text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded border border-slate-200">
                                                            <span class="text-slate-400 select-none">SHA256:</span> {{ hash('sha256', $log->id . $log->created_at . $log->action) }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right ml-4 shrink-0">
                                                    <span class="text-xs font-medium text-slate-400 bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                                                        {{ $log->created_at->format('d M Y, H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="pl-8 py-4 text-slate-500 text-sm italic relative">
                                            <div class="absolute -left-[9px] top-5 w-4 h-4 rounded-full bg-slate-50 border-2 border-slate-200"></div>
                                            Belum ada log aktivitas tercatat.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endhasanyrole

                    {{-- ========================================== --}}
                    {{-- SEKSI VENDOR (PBI 03, 06, 07, 10)          --}}
                    {{-- ========================================== --}}
                    @role('vendor')
                        {{-- VENDOR SECTION REMAINS UNCHANGED AS IT WAS DECLARED FINAL BEFORE --}}
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
                                        <div class="relative flex items-center">
                                            <span class="absolute left-0 top-0 bottom-0 flex items-center justify-center w-16 bg-slate-800 text-slate-300 font-black text-xl rounded-l-2xl border-r border-white/10">Rp</span>
                                            <input type="number" name="offered_price" class="w-full bg-white/5 border-white/10 rounded-2xl text-white py-5 pl-20 pr-6 focus:ring-2 focus:ring-emerald-500 focus:bg-white/10 text-xl font-black transition-all" placeholder="0" required />
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-5 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-2xl shadow-[0_20px_50px_rgba(16,185,129,0.3)] transition-all active:scale-[0.99] uppercase tracking-widest disabled:opacity-50 disabled:cursor-not-allowed" @if($availableTenders->isEmpty()) disabled @endif>
                                    🔒 Submit Sealed Bid
                                </button>
                                <p class="text-center text-[10px] text-slate-400 font-medium italic">Semua data harga akan dikunci seketika menggunakan algoritma AES-256-CBC Encryption Engine.</p>
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