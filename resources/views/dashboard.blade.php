<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    
                    {{-- SEKSI ADMIN / SURVEYOR --}}
                    @role('admin')
                        <div class="mb-8">
                            <h1 class="text-2xl font-extrabold text-gray-900">Halo Admin!</h1>
                            <p class="text-gray-500">Panel kendali verifikasi lapangan sistem **INAPROC+**.</p>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                            <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                                <span class="mr-2 text-indigo-600">📋</span> {{ __('Input Laporan Survey Lapangan') }}
                            </h3>
                            
                            <form action="{{ route('survey.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="user_id" :value="__('Pilih Vendor')" />
                                        <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500" required>
                                            <option value="">-- Pilih Vendor --</option>
                                            @foreach(\App\Models\User::role('vendor')->get() as $vendor)
                                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="office_condition" :value="__('Kondisi Kantor')" />
                                        <select name="office_condition" id="office_condition" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" required>
                                            <option value="Layak">Layak / Sesuai</option>
                                            <option value="Tidak Layak">Tidak Layak / Fiktif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="infrastructure_score" :value="__('Skor Infrastruktur (0-100)')" />
                                        <x-text-input type="number" name="infrastructure_score" class="mt-1 block w-full rounded-lg" placeholder="70" required min="0" max="100" />
                                    </div>
                                    <div>
                                        <x-input-label for="survey_photo" :value="__('Bukti Foto Lokasi')" />
                                        <input type="file" name="survey_photo" id="survey_photo" class="mt-1 block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-white" required>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="notes" :value="__('Catatan Tambahan')" />
                                    <textarea name="notes" rows="2" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500" placeholder="Catatan hasil audit..."></textarea>
                                </div>

                                <x-primary-button class="w-full justify-center py-3 bg-indigo-600 hover:bg-indigo-700">
                                    {{ __('Simpan & Verifikasi Vendor') }}
                                </x-primary-button>
                            </form>
                        </div>

                        {{-- RIWAYAT SURVEY --}}
                        <div class="mt-10 pt-6 border-t border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Log Review Vendor</h3>
                            <div class="overflow-x-auto rounded-xl border border-gray-200">
                                <table class="min-w-full bg-white text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="py-3 px-4 border-b text-left">Vendor</th>
                                            <th class="py-3 px-4 border-b text-center">Skor</th>
                                            <th class="py-3 px-4 border-b text-center">Foto</th>
                                            <th class="py-3 px-4 border-b text-right">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($surveyReports ?? [] as $report)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="py-3 px-4 font-semibold">{{ $report->vendor->name }}</td>
                                                <td class="py-3 px-4 text-center">{{ $report->infrastructure_score }}%</td>
                                                <td class="py-3 px-4 text-center">
                                                    <img src="{{ asset('storage/' . $report->survey_photo) }}" class="h-8 w-12 object-cover mx-auto rounded shadow-sm">
                                                </td>
                                                <td class="py-3 px-4 text-right">
                                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $report->infrastructure_score >= 70 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                        {{ $report->infrastructure_score >= 70 ? 'Verified' : 'Rejected' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="py-10 text-center text-gray-400 italic">Belum ada laporan survey.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    {{-- SEKSI VENDOR (Digital Trust Card) --}}
                    @else
                        @role('vendor')
                            <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- UNIK: Kartu Sertifikasi Digital --}}
                                <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-black rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
                                    <div class="relative z-10">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-xs font-mono text-indigo-300 tracking-widest uppercase">Verification Certificate</p>
                                                <h2 class="text-3xl font-black mt-1 uppercase tracking-tighter">{{ Auth::user()->name }}</h2>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-[10px] bg-white/10 px-2 py-1 rounded border border-white/20 font-mono">ID: {{ Auth::user()->id }}{{ date('s') }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-12 flex items-center">
                                            @if(Auth::user()->status == 'verified')
                                                <div class="h-14 w-14 bg-green-500 rounded-full flex items-center justify-center shadow-lg shadow-green-500/50">
                                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-green-400 font-bold text-xl tracking-wide">STATUS: VERIFIED</p>
                                                    <p class="text-xs text-gray-400">Terverifikasi oleh Tim Auditor INAPROC+</p>
                                                </div>
                                            @elseif(Auth::user()->status == 'rejected')
                                                <div class="h-14 w-14 bg-red-500 rounded-full flex items-center justify-center">
                                                    <span class="text-2xl font-black text-white">!</span>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-red-400 font-bold text-xl">STATUS: REJECTED</p>
                                                    <p class="text-xs text-gray-400">Silakan hubungi admin untuk audit ulang.</p>
                                                </div>
                                            @else
                                                <div class="h-14 w-14 bg-yellow-500 rounded-full flex items-center justify-center animate-pulse">
                                                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-yellow-400 font-bold text-xl">STATUS: PENDING</p>
                                                    <p class="text-xs text-gray-400">Menunggu antrian survey lapangan.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Dekorasi Abstract --}}
                                    <div class="absolute -bottom-10 -right-10 h-40 w-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
                                </div>

                                {{-- Tips Section --}}
                                <div class="bg-indigo-50 rounded-3xl p-8 border border-indigo-100 flex flex-col justify-center">
                                    <h3 class="text-indigo-900 font-bold text-lg mb-3 flex items-center">
                                        <span class="mr-2">💡</span> Tips Percepatan Verifikasi
                                    </h3>
                                    <ul class="text-indigo-700/80 text-sm space-y-3 font-medium">
                                        <li class="flex items-start"><span class="mr-2">🚀</span> Lengkapi data Geotagging Office (PBI-11).</li>
                                        <li class="flex items-start"><span class="mr-2">📷</span> Unggah portofolio dengan resolusi tinggi.</li>
                                        <li class="flex items-start"><span class="mr-2">🏢</span> Siapkan dokumen fisik saat petugas datang.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- INPUT PORTOFOLIO --}}
                            <div class="mt-10">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <span class="mr-2">🖼️</span> {{ __('Unggah Portofolio Proyek') }}
                                </h3>
                                <form action="{{ route('portfolio.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-xl bg-gray-50 p-6 rounded-2xl border border-gray-100">
                                    @csrf
                                    <div>
                                        <x-input-label for="title" :value="__('Nama Proyek')" />
                                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full rounded-lg" required placeholder="Contoh: Pengadaan CCTV" />
                                    </div>
                                    <div>
                                        <x-input-label for="description" :value="__('Deskripsi')" />
                                        <textarea id="description" name="description" rows="2" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500" placeholder="Detail proyek..."></textarea>
                                    </div>
                                    <div>
                                        <x-input-label for="portfolio_file" :value="__('Bukti Visual')" />
                                        <input type="file" name="portfolio_file" id="portfolio_file" class="mt-1 block w-full text-sm rounded-lg" required>
                                    </div>
                                    <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200">
                                        {{ __('Unggah Bukti Nyata') }}
                                    </x-primary-button>
                                </form>
                            </div>

                            {{-- RIWAYAT PORTOFOLIO --}}
                            <div class="mt-12 pt-8 border-t border-gray-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-6">Galeri Portofolio Anda</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    @forelse($portfolios ?? [] as $item)
                                        <div class="group relative bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                                            @if($item->file_type === 'video')
                                                <video class="w-full h-44 object-cover" controls><source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4"></video>
                                            @else
                                                <img src="{{ asset('storage/' . $item->file_path) }}" class="w-full h-44 object-cover group-hover:scale-105 transition duration-500">
                                            @endif
                                            <div class="p-4">
                                                <h4 class="font-bold text-gray-800 text-sm truncate uppercase">{{ $item->title }}</h4>
                                                <p class="text-[11px] text-gray-500 mt-1 line-clamp-1">{{ $item->description }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-full py-16 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                                            <p class="text-gray-400 font-medium">Belum ada portofolio visual.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endrole
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>