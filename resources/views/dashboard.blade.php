<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @role('admin')
                        <h1 class="text-2xl font-bold">Halo Admin! [cite: 165]</h1>
                        <p>Anda memiliki akses penuh ke seluruh sistem INAPROC+.</p>
                    @endrole

                    @role('vendor')
                        <h1 class="text-2xl font-bold">Halo Vendor! [cite: 167]</h1>
                        <p class="mb-4">Silakan lengkapi data geotagging Anda pada menu yang tersedia.</p>
                        
                        <hr class="my-6">

                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                {{ __('Unggah Portofolio Proyek (Visual Evidence)') }} [cite: 87]
                            </h3>
                            
                            <form action="{{ route('portfolio.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-xl">
                                @csrf
                                
                                <div>
                                    <x-input-label for="title" :value="__('Nama Proyek / Pekerjaan')" />
                                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required placeholder="Contoh: Pengadaan Laptop Kantor Tahap I" />
                                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                                </div>

                                <div>
                                    <x-input-label for="description" :value="__('Deskripsi Singkat')" />
                                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Jelaskan secara singkat pengerjaan proyek ini..."></textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                </div>

                                <div>
                                    <x-input-label for="portfolio_file" :value="__('Bukti Visual (Foto/Video)')" />
                                    <div class="mt-1 flex items-center">
                                        <input type="file" name="portfolio_file" id="portfolio_file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" required>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, MP4. Maksimal 5MB (Sesuai RNF-03)[cite: 503].</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('portfolio_file')" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-primary-button>
                                        {{ __('Unggah Bukti Nyata') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>

                        <div class="mt-12 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">
                                {{ __('Riwayat Portofolio Visual Anda') }} [cite: 173]
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @forelse($portfolios as $item)
                                    <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden shadow-sm transition hover:shadow-md">
                                        @if($item->file_type === 'video')
                                            <video class="w-full h-48 object-cover bg-black" controls>
                                                <source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4">
                                            </video>
                                        @else
                                            <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                                        @endif

                                        <div class="p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <h4 class="font-bold text-gray-800 leading-tight">{{ $item->title }}</h4>
                                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide bg-indigo-100 text-indigo-700 rounded-full">
                                                    {{ $item->file_type }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-600 line-clamp-2">{{ $item->description }}</p>
                                            <p class="text-[10px] text-gray-400 mt-3 italic">Diunggah pada: {{ $item->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full py-10 text-center bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                                        <p class="text-gray-500 italic">Belum ada portofolio visual yang diunggah. [cite: 61]</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>