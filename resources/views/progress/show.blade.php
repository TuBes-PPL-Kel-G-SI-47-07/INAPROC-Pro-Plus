<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-tighter flex items-center">
            <span class="mr-3 text-3xl">📊</span>
            {{ __('Visual Progress Detail: ') }} {{ $project->item_name }}
        </h2>
    </x-slot>

    <!-- Leaflet.js CSS & JS CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="py-12 relative overflow-hidden min-h-screen bg-slate-50">
        <!-- Background decorative blurs -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-indigo-500/5 rounded-full blur-[140px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10 space-y-8">
            
            {{-- ACTIONS MESSAGES --}}
            @if(session('success'))
                <div class="bg-indigo-900 border-l-4 border-indigo-500 text-indigo-100 px-6 py-4 rounded-3xl shadow-xl animate-pulse flex items-center justify-between" role="alert">
                    <div>
                        <p class="font-black text-base">Berhasil!</p>
                        <p class="text-sm text-indigo-200 mt-1">{{ session('success') }}</p>
                    </div>
                    <span class="text-2xl">✨</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- LEFT COLUMN: INFO & SUBMISSION FORM --}}
                <div class="lg:col-span-1 space-y-8">
                    
                    {{-- PROJECT DETAIL CARD --}}
                    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl"></div>
                        <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center">
                            <span class="mr-3 text-xl">📋</span> Detail Proyek
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block">Nama Paket Pengadaan</span>
                                <span class="text-base font-black text-gray-800">{{ $project->item_name }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block">Deskripsi Kerja</span>
                                <span class="text-xs text-gray-600 font-medium block mt-1">{{ $project->description ?? 'Tidak ada deskripsi.' }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 border-t border-gray-50 pt-4">
                                <div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block">Unit Pemohon</span>
                                    <span class="text-xs font-black text-gray-800 block mt-1">{{ $project->user->name ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block">Pelaksana/Vendor</span>
                                    <span class="text-xs font-black text-gray-800 block mt-1">{{ $project->vendor->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="border-t border-gray-50 pt-4">
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block">Nilai Kontrak</span>
                                <span class="text-xl font-black text-indigo-600 tracking-tighter">Rp {{ number_format($project->total_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t border-gray-50 pt-4">
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block">Total Progres Saat Ini</span>
                                @php
                                    $currentMaxProgress = $project->progresses()->where('status', 'approved')->max('percentage') ?? 0;
                                @endphp
                                <div class="flex items-center justify-between mt-2">
                                    <div class="h-3 flex-1 bg-gray-100 rounded-full overflow-hidden p-[1px] mr-4">
                                        <div class="h-full bg-indigo-600 rounded-full shadow-[0_0_10px_rgba(79,70,229,0.3)]" style="width: {{ $currentMaxProgress }}%"></div>
                                    </div>
                                    <span class="text-lg font-black text-indigo-600 leading-none">{{ $currentMaxProgress }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- VENDOR SUBMISSION FORM --}}
                    @role('vendor')
                        @if($project->vendor_id === Auth::id())
                            <div class="bg-slate-900 p-8 rounded-[2.5rem] text-white shadow-2xl border border-white/5 relative overflow-hidden">
                                <div class="absolute -top-10 -left-10 w-40 h-40 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
                                <h3 class="text-lg font-black tracking-tight mb-6 flex items-center">
                                    <span class="mr-3 text-xl">📤</span> Kirim Progres Visual
                                </h3>
                                <form action="{{ route('progress.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                    @csrf
                                    <input type="hidden" name="procurement_request_id" value="{{ $project->id }}">

                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Persentase Target (%)</label>
                                        <select name="percentage" class="w-full bg-white/5 border-white/10 rounded-2xl text-white py-4 px-5 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-sm" required>
                                            <option value="" class="text-black" disabled selected>-- Pilih Capaian Progres --</option>
                                            @for($i = 10; $i <= 100; $i += 10)
                                                <option value="{{ $i }}" class="text-black" {{ $currentMaxProgress >= $i ? 'disabled' : '' }}>
                                                    {{ $i }}% {{ $currentMaxProgress >= $i ? '(Sudah Tercapai)' : '' }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Deskripsi Pengerjaan</label>
                                        <textarea name="description" rows="3" class="w-full bg-white/5 border-white/10 rounded-2xl text-white py-4 px-5 focus:ring-2 focus:ring-indigo-500 transition-all text-xs font-semibold resize-none" placeholder="Tuliskan perkembangan fisik proyek lapangan..." required></textarea>
                                    </div>

                                    <div class="bg-white/5 p-6 rounded-3xl border border-white/10 text-center">
                                        <label class="block text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-3">Foto Progres Real-time</label>
                                        <input type="file" name="progress_photo" class="block w-full text-xs text-slate-300 file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition file:cursor-pointer" accept="image/jpeg,image/jpg" required>
                                        <span class="text-[9px] text-slate-500 block mt-3 font-medium">Unggah foto berformat JPG/JPEG untuk mendukung ekstraksi metadata koordinat GPS asli (Maks 5MB).</span>
                                    </div>

                                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-2xl shadow-xl transition-all active:scale-[0.99] uppercase tracking-widest text-xs">
                                        Unggah & Verifikasi GPS
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endrole

                    {{-- BAST SUBMISSION & STATUS CARD (PBI-17) --}}
                    @php
                        $bast = $project->bastSubmission;
                    @endphp

                    @if($bast)
                        {{-- BAST STATUS DISPLAY CARD --}}
                        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl"></div>
                            <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center">
                                <span class="mr-3 text-xl">📄</span> Berita Acara (BAST)
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100/80">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Status BAST</span>
                                        @if($bast->status === 'approved')
                                            <span class="bg-green-50 text-green-700 border border-green-200 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Approved</span>
                                        @elseif($bast->status === 'rejected')
                                            <span class="bg-red-50 text-red-700 border border-red-200 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Rejected</span>
                                        @else
                                            <span class="bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest animate-pulse">Pending Review</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 font-medium leading-relaxed">{{ $bast->description ?? 'Tidak ada deskripsi BAST.' }}</p>
                                    
                                    <a href="{{ route('bast.download', $bast->id) }}" class="mt-4 flex items-center justify-center gap-2 w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl text-xs uppercase tracking-widest transition-all">
                                        <span>📥</span> Unduh Dokumen BAST
                                    </a>
                                </div>

                                {{-- AUDITOR NOTES FOR BAST --}}
                                @if($bast->auditor_notes)
                                    <div class="p-4 bg-amber-50 text-amber-900 border border-amber-100 rounded-2xl text-xs font-semibold">
                                        <p class="font-black mb-1">Catatan Auditor:</p>
                                        <p class="italic">"{{ $bast->auditor_notes }}"</p>
                                    </div>
                                @endif

                                {{-- AUDITOR VERIFY FORM FOR BAST (PBI-18 Ground Work) --}}
                                @role('auditor')
                                    @if($bast->status === 'pending')
                                        <div class="border-t border-gray-50 pt-6">
                                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-4">Verifikasi BAST</h4>
                                            <form action="{{ route('bast.verify', $bast->id) }}" method="POST" class="space-y-4">
                                                @csrf
                                                <div>
                                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-2">Tentukan Keputusan</label>
                                                    <select name="status" class="w-full bg-gray-50 border-none rounded-xl text-xs py-3 px-4 font-bold" required>
                                                        <option value="approved">Setujui BAST (Proyek Selesai)</option>
                                                        <option value="rejected">Tolak BAST (Minta Revisi)</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-2">Catatan Final</label>
                                                    <textarea name="auditor_notes" class="w-full bg-gray-50 border-none rounded-xl text-xs py-3 px-4 font-semibold resize-none h-20" placeholder="Tulis catatan persetujuan/penolakan BAST..." required></textarea>
                                                </div>
                                                <button type="submit" class="w-full py-4 bg-slate-900 hover:bg-green-600 text-white font-black text-xs rounded-xl tracking-widest uppercase transition-all shadow-md">
                                                    Simpan Keputusan Final
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endrole

                                {{-- VENDOR RE-UPLOAD FORM IF REJECTED --}}
                                @role('vendor')
                                    @if($project->vendor_id === Auth::id() && $bast->status === 'rejected')
                                        <div class="border-t border-gray-50 pt-6">
                                            <h4 class="text-xs font-black text-red-500 uppercase tracking-widest mb-4">Unggah Ulang Dokumen BAST</h4>
                                            <form action="{{ route('bast.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                                @csrf
                                                <input type="hidden" name="procurement_request_id" value="{{ $project->id }}">
                                                <div>
                                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-2">Pilih File Baru</label>
                                                    <input type="file" name="bast_file" class="block w-full text-xs text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-red-500 file:text-white" accept=".pdf,.docx,.jpg,.png" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-2">Keterangan Revisi</label>
                                                    <textarea name="description" rows="2" class="w-full bg-gray-50 border-none rounded-xl text-xs py-3 px-4 font-semibold resize-none" placeholder="Tuliskan keterangan mengenai dokumen baru..." required></textarea>
                                                </div>
                                                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs rounded-xl tracking-widest uppercase transition-all shadow-md">
                                                    Kirim Ulang Dokumen
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endrole
                            </div>
                        </div>
                    @else
                        {{-- NO BAST SUBMITTED YET --}}
                        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl"></div>
                            <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center">
                                <span class="mr-3 text-xl">📄</span> Dokumen BAST
                            </h3>
                            
                            @if($currentMaxProgress >= 100)
                                @role('vendor')
                                    @if($project->vendor_id === Auth::id())
                                        {{-- Vendor can upload BAST --}}
                                        <div class="space-y-4">
                                            <p class="text-xs text-gray-500 font-semibold leading-relaxed">Selamat! Proyek telah mencapai progres 100%. Silakan unggah dokumen Berita Acara Serah Terima (BAST) untuk verifikasi audit.</p>
                                            
                                            <form action="{{ route('bast.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                                @csrf
                                                <input type="hidden" name="procurement_request_id" value="{{ $project->id }}">
                                                
                                                <div>
                                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-2">Dokumen BAST (.pdf, .docx, .jpg, .png)</label>
                                                    <input type="file" name="bast_file" class="block w-full text-xs text-gray-950 file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-indigo-600 file:text-white" accept=".pdf,.docx,.jpg,.png" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-2">Catatan Tambahan</label>
                                                    <textarea name="description" rows="2" class="w-full bg-gray-50 border-none rounded-xl text-xs py-3 px-4 font-semibold resize-none" placeholder="Tulis deskripsi atau catatan penyerahan..."></textarea>
                                                </div>
                                                
                                                <button type="submit" class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-black rounded-2xl shadow-xl transition-all active:scale-[0.99] uppercase tracking-widest text-xs">
                                                    Kirim Dokumen BAST
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <p class="text-xs text-amber-600 font-bold bg-amber-50 border border-amber-200/50 p-4 rounded-2xl">Menunggu Vendor mengunggah dokumen BAST.</p>
                                    @endif
                                @else
                                    <p class="text-xs text-amber-600 font-bold bg-amber-50 border border-amber-200/50 p-4 rounded-2xl">Menunggu Vendor mengunggah dokumen BAST.</p>
                                @endrole
                            @else
                                <div class="p-5 bg-gray-50 border border-gray-100 rounded-2xl text-center">
                                    <span class="text-2xl mb-2 block">🔒</span>
                                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">BAST Terkunci</p>
                                    <p class="text-[10px] text-gray-400 font-semibold leading-normal">
                                        Form BAST akan aktif setelah progres proyek yang disetujui oleh Auditor mencapai 100%.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>

                {{-- MIDDLE COLUMN: TIMELINE --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- ROADMAP TIMELINE CARD --}}
                    <div class="bg-white p-8 md:p-12 rounded-[3rem] border border-gray-100 shadow-xl relative">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-600/5 rounded-full blur-[80px]"></div>
                        
                        <h3 class="text-2xl font-black text-gray-900 mb-10 flex items-center relative z-10">
                            <span class="mr-4 bg-indigo-50 text-indigo-600 p-3 rounded-2xl shadow-inner">📈</span> Timeline Pengerjaan Fisik
                        </h3>

                        <div class="relative pl-8 border-l-2 border-indigo-100/80 space-y-12">
                            
                            @forelse($project->progresses as $progress)
                                <div class="relative group">
                                    
                                    {{-- Timeline bullet --}}
                                    <div class="absolute -left-[41px] top-1.5 h-6 w-6 rounded-full border-4 border-white shadow-md flex items-center justify-center transition-all duration-300
                                        {{ $progress->status === 'approved' ? 'bg-green-500 shadow-green-100' : ($progress->status === 'anomaly' ? 'bg-amber-500 shadow-amber-100' : ($progress->status === 'rejected' ? 'bg-red-500 shadow-red-100' : 'bg-blue-500 shadow-blue-100')) }}
                                        group-hover:scale-125">
                                    </div>

                                    {{-- Capaian box --}}
                                    <div class="bg-gray-50/50 hover:bg-white rounded-[2rem] p-6 md:p-8 border border-gray-100 hover:border-indigo-100 hover:shadow-xl transition-all relative">
                                        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                                            <div class="flex items-center space-x-4">
                                                <span class="bg-indigo-600 text-white font-black text-lg px-4 py-2 rounded-2xl shadow-md">
                                                    {{ $progress->percentage }}%
                                                </span>
                                                <div>
                                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider block">Tanggal Unggah</span>
                                                    <span class="text-xs font-black text-gray-800">{{ $progress->created_at->format('d M Y, H:i') }}</span>
                                                </div>
                                            </div>

                                            <div>
                                                @if($progress->status === 'approved')
                                                    <span class="bg-green-50 text-green-700 border border-green-200 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">Verified</span>
                                                @elseif($progress->status === 'anomaly')
                                                    <span class="bg-amber-50 text-amber-700 border border-amber-200 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest animate-pulse">⚠️ Anomaly Detected</span>
                                                @elseif($progress->status === 'rejected')
                                                    <span class="bg-red-100 text-red-800 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">Rejected</span>
                                                @else
                                                    <span class="bg-blue-50 text-blue-700 border border-blue-200 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">Pending Audit</span>
                                                @endif
                                            </div>
                                        </div>

                                        <p class="text-sm font-semibold text-gray-700 leading-relaxed mb-6">
                                            {{ $progress->description }}
                                        </p>

                                        {{-- Visual Photo & EXIF Metadata box --}}
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start bg-white p-5 rounded-2xl border border-gray-100">
                                            <div class="overflow-hidden rounded-xl border border-gray-100 shadow-sm relative group/img cursor-pointer">
                                                <img src="{{ asset('storage/' . $progress->photo_path) }}" class="w-full h-40 object-cover group-hover/img:scale-105 transition-transform duration-300" alt="Progress Photo">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-black uppercase tracking-widest">
                                                    Lihat Foto Penuh
                                                </div>
                                            </div>
                                            
                                            <div class="space-y-3">
                                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Metadata Foto (EXIF)</h4>
                                                @if($progress->latitude && $progress->longitude)
                                                    <div class="space-y-2 text-xs font-medium text-gray-700">
                                                        <div class="flex items-center space-x-2">
                                                            <span>📍</span>
                                                            <span class="font-mono">Lat: {{ number_format($progress->latitude, 6) }}, Lng: {{ number_format($progress->longitude, 6) }}</span>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <span>📅</span>
                                                            <span>Waktu Foto: {{ $progress->taken_at ? $progress->taken_at->format('d M Y, H:i') : 'N/A' }}</span>
                                                        </div>
                                                        <span class="text-[9px] bg-green-500/10 text-green-700 px-3 py-1 rounded-full font-black uppercase tracking-wider inline-block">Integrasi GPS Sukses</span>
                                                    </div>
                                                @else
                                                    <div class="p-3 bg-red-50 text-red-800 rounded-xl space-y-1">
                                                        <p class="text-xs font-black flex items-center">
                                                            <span class="mr-2">🚨</span> Metadata Tidak Ditemukan
                                                        </p>
                                                        <p class="text-[9px] font-medium leading-normal text-red-600">
                                                            Foto tidak memiliki geotagging GPS atau timestamp asli. Sistem menandainya sebagai temuan anomalistis.
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- AUDITOR INTERVENTION ACTION --}}
                                        @role('auditor')
                                            <div class="mt-6 pt-6 border-t border-gray-100/80 space-y-4">
                                                <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Evaluasi Auditor</h4>
                                                <form action="{{ route('progress.verify', $progress->id) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-2">Tentukan Status</label>
                                                            <select name="status" class="w-full bg-gray-50 border-none rounded-xl text-xs py-3 px-4 font-bold" required>
                                                                <option value="approved" {{ $progress->status === 'approved' ? 'selected' : '' }}>Verifikasi Progres (Approve)</option>
                                                                <option value="anomaly" {{ $progress->status === 'anomaly' ? 'selected' : '' }}>Flag as Anomaly (Anomali)</option>
                                                                <option value="rejected" {{ $progress->status === 'rejected' ? 'selected' : '' }}>Tolak Progres (Reject)</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-2">Catatan Audit</label>
                                                            <input type="text" name="auditor_notes" class="w-full bg-gray-50 border-none rounded-xl text-xs py-3 px-4 font-semibold" value="{{ $progress->auditor_notes }}" placeholder="Tulis catatan audit di sini...">
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-indigo-600 text-white font-black text-xs rounded-xl tracking-widest uppercase transition-all shadow-md">
                                                        Simpan Hasil Evaluasi
                                                    </button>
                                                </form>
                                            </div>
                                        @endrole

                                        {{-- AUDITOR NOTES DISPLAY --}}
                                        @if($progress->auditor_notes)
                                            <div class="mt-4 p-4 bg-indigo-50/50 rounded-xl border border-indigo-100/30">
                                                <span class="text-[10px] font-black text-indigo-700 uppercase tracking-wider block">Catatan Evaluasi Auditor:</span>
                                                <p class="text-xs font-semibold text-gray-700 mt-1 italic">"{{ $progress->auditor_notes }}"</p>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            @empty
                                <div class="py-16 text-center">
                                    <div class="inline-block p-8 bg-gray-50 border border-gray-100 rounded-3xl">
                                        <span class="text-3xl mb-3 block">🚧</span>
                                        <p class="text-gray-400 font-bold italic text-sm">Belum ada progres pengerjaan diunggah.</p>
                                    </div>
                                </div>
                            @endforelse

                        </div>
                    </div>

                    {{-- GEOSPATIAL MAP CARD (Leaflet.js) --}}
                    @php
                        $validProgresses = $project->progresses->filter(function($p) {
                            return $p->latitude !== null && $p->longitude !== null;
                        });
                    @endphp

                    @if($validProgresses->count() > 0)
                        <div class="bg-white p-8 md:p-12 rounded-[3rem] border border-gray-100 shadow-xl relative overflow-hidden">
                            <h3 class="text-2xl font-black text-gray-900 mb-8 flex items-center">
                                <span class="mr-4 bg-indigo-50 text-indigo-600 p-3 rounded-2xl shadow-inner">🗺️</span> 
                                Pemetaan Geospasial Progress
                            </h3>

                            <!-- Map container -->
                            <div id="map" class="h-96 w-full rounded-3xl shadow-md border border-gray-200 relative z-10"></div>
                            
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    // Initialize Leaflet Map centered on the first progress coordinates
                                    var firstProgress = @json($validProgresses->first());
                                    var map = L.map('map').setView([firstProgress.latitude, firstProgress.longitude], 13);

                                    // Add OpenStreetMap tiles
                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
                                    }).addTo(map);

                                    // Array of progress coordinate points
                                    var progresses = @json($validProgresses->values());

                                    progresses.forEach(function (prog) {
                                        var popupContent = `
                                            <div class="p-3 text-slate-800 max-w-xs">
                                                <h4 class="font-black text-sm uppercase text-indigo-600 mb-1">${prog.percentage}% Progress</h4>
                                                <p class="text-xs font-semibold mb-3">${prog.description}</p>
                                                <img src="/storage/${prog.photo_path}" class="w-full h-24 object-cover rounded-lg border shadow-sm">
                                                <div class="text-[9px] text-gray-400 mt-2 font-mono">Lat: ${prog.latitude}, Lng: ${prog.longitude}</div>
                                            </div>
                                        `;

                                        var marker = L.marker([prog.latitude, prog.longitude]).addTo(map);
                                        marker.bindPopup(popupContent);
                                    });

                                    // Draw line path representing roadmap pengerjaan vendor
                                    var pathCoords = progresses.map(function(p) {
                                        return [p.latitude, p.longitude];
                                    });
                                    if(pathCoords.length > 1) {
                                        var polyline = L.polyline(pathCoords, {color: '#4f46e5', weight: 4, dashArray: '5, 10'}).addTo(map);
                                        map.fitBounds(polyline.getBounds());
                                    }
                                });
                            </script>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
