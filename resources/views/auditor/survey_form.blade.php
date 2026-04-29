<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-tighter flex items-center">
            <span class="mr-3 text-3xl">📋</span>
            {{ __('Input Laporan Survey Lapangan') }}
        </h2>
    </x-slot>

    <div class="py-12 relative overflow-hidden min-h-screen bg-slate-50">
        <!-- Dekorasi Background -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white/80 backdrop-blur-xl overflow-hidden shadow-2xl sm:rounded-[3rem] border border-white">
                <div class="p-10 md:p-14">
                    <div class="mb-10 text-center">
                        <h1 class="text-4xl font-black text-gray-900 tracking-tighter mb-2">Penilaian Vendor</h1>
                        <p class="text-gray-500 font-medium">Lengkapi laporan survey lapangan untuk vendor <strong class="text-indigo-600">{{ $vendor->name }}</strong>.</p>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-8 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-2xl shadow-sm">
                            <p class="font-bold mb-2 text-sm">Terdapat kesalahan pengisian:</p>
                            <ul class="list-disc pl-5 text-xs space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('survey.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $vendor->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Kondisi Kantor -->
                            <div class="bg-gray-50/50 p-6 rounded-3xl border border-gray-100">
                                <x-input-label for="office_condition" :value="__('Kondisi Kantor')" class="font-black text-sm text-gray-700 ml-2 mb-3" />
                                <div class="relative">
                                    <select name="office_condition" id="office_condition" class="block w-full bg-white border-none rounded-2xl py-4 px-5 focus:ring-2 focus:ring-indigo-500 text-sm font-medium shadow-sm transition-all appearance-none" required>
                                        <option value="" disabled selected>-- Pilih Penilaian --</option>
                                        <option value="Layak">Layak (100 Poin)</option>
                                        <option value="Cukup Layak">Cukup Layak (50 Poin)</option>
                                        <option value="Tidak Layak">Tidak Layak (0 Poin)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Skor Infrastruktur -->
                            <div class="bg-gray-50/50 p-6 rounded-3xl border border-gray-100">
                                <x-input-label for="infrastructure_score" :value="__('Skor Infrastruktur (1-100)')" class="font-black text-sm text-gray-700 ml-2 mb-3" />
                                <div class="relative">
                                    <x-text-input type="number" name="infrastructure_score" id="infrastructure_score" class="block w-full bg-white border-none rounded-2xl py-4 px-5 focus:ring-2 focus:ring-indigo-500 text-sm font-black text-indigo-900 shadow-sm transition-all" min="1" max="100" placeholder="0 - 100" required />
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Survey -->
                        <div class="bg-gray-50/50 p-6 rounded-3xl border border-gray-100">
                            <x-input-label for="notes" :value="__('Catatan Auditor')" class="font-black text-sm text-gray-700 ml-2 mb-3" />
                            <textarea name="notes" id="notes" class="block w-full bg-white border-none rounded-2xl py-4 px-5 focus:ring-2 focus:ring-indigo-500 text-sm font-medium shadow-sm transition-all resize-none h-32" placeholder="Tuliskan detail temuan lapangan jika ada..."></textarea>
                        </div>

                        <!-- Upload Foto -->
                        <div class="bg-indigo-50/30 p-8 rounded-3xl border border-indigo-100/50 flex flex-col items-center justify-center border-dashed text-center group transition-colors hover:bg-indigo-50/60">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform">
                                <span class="text-2xl">📸</span>
                            </div>
                            <x-input-label for="survey_photo" :value="__('Unggah Bukti Foto Real-time')" class="font-black text-indigo-900 text-lg mb-2 cursor-pointer" />
                            <p class="text-xs text-indigo-500 font-medium mb-5">Format file: JPG, PNG, JPEG (Max 2MB)</p>
                            <input type="file" name="survey_photo" id="survey_photo" class="block w-full max-w-xs text-xs text-indigo-900 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer transition-all file:shadow-lg" accept="image/*" required>
                        </div>

                        <div class="pt-6 flex gap-4">
                            <a href="{{ route('dashboard') }}" class="flex-1 py-5 bg-gray-100 text-gray-600 font-black rounded-2xl text-center uppercase tracking-widest text-sm hover:bg-gray-200 transition-colors">
                                Kembali
                            </a>
                            <button type="submit" class="flex-[2] py-5 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-600/30 transition-all active:scale-[0.99] uppercase tracking-widest text-sm">
                                Simpan Laporan & Auto-Score
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
