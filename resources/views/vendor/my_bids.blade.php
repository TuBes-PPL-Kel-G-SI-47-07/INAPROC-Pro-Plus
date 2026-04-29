<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-tighter">
            {{ __('Riwayat Penawaran (My Bids)') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        showEditModal: false, 
        editTenderId: '', 
        editTenderTitle: '', 
        editPrice: '' 
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- NOTIFICATIONS --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-xl shadow-sm animate-pulse" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-[3rem] border border-gray-100 relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-600/5 rounded-full blur-[80px]"></div>
                
                <div class="p-8 md:p-12 relative z-10">
                    <div class="mb-10">
                        <h1 class="text-4xl font-black text-gray-900 tracking-tighter">Log Penawaran Vendor</h1>
                        <p class="text-gray-500 mt-2 font-medium">Pantau status tender dan lakukan revisi harga selama periode masih terbuka.</p>
                    </div>

                    <div class="overflow-x-auto rounded-[2rem] border border-gray-100 shadow-sm">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-900 text-white font-black uppercase text-[10px] tracking-[0.2em]">
                                <tr>
                                    <th class="px-8 py-6 rounded-tl-[2rem]">Paket Tender</th>
                                    <th class="px-6 py-6 text-center">Status Tender</th>
                                    <th class="px-6 py-6 text-center">Status Bid</th>
                                    <th class="px-8 py-6 text-right">Harga Penawaran</th>
                                    <th class="px-8 py-6 text-center rounded-tr-[2rem]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($bids as $bid)
                                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                                        <td class="px-8 py-6">
                                            <div class="font-black text-gray-800 text-base">{{ $bid->tender->title ?? 'Tender Terhapus' }}</div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Tender ID: #{{ $bid->tender_id }}</div>
                                        </td>
                                        <td class="px-6 py-6 text-center">
                                            @if(($bid->tender->status ?? '') == 'open')
                                                <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-green-200">OPEN</span>
                                            @else
                                                <span class="bg-red-100 text-red-700 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-red-200">CLOSED</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-6 text-center">
                                            @if($bid->status == 'winner')
                                                <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-green-200 shadow-sm flex items-center justify-center gap-2">
                                                    <span>🏆</span> Lolos / Pemenang
                                                </span>
                                            @elseif($bid->status == 'rejected')
                                                <span class="bg-red-100 text-red-700 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-red-200 shadow-sm">
                                                    Tidak Lolos
                                                </span>
                                            @else
                                                <span class="bg-blue-100 text-blue-700 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-blue-200 shadow-sm">
                                                    {{ $bid->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <div class="flex flex-col items-end">
                                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Encrypted</span>
                                                <span class="font-black text-xl text-indigo-600 tracking-tighter">Rp {{ number_format((float) $bid->getDecryptedPrice(), 0, ',', '.') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            @if($bid->status == 'winner')
                                                <div class="flex flex-col items-center gap-2">
                                                    <span class="text-[10px] text-green-600 font-bold uppercase">Selamat, Anda Menang!</span>
                                                    <a href="{{ route('procurement.spk', $bid->tender->procurement_request_id ?? 0) }}" target="_blank" class="bg-green-600 border border-green-700 text-white px-6 py-2.5 rounded-xl text-[10px] font-black hover:bg-green-700 transition-all shadow-lg active:scale-95 tracking-widest uppercase flex items-center justify-center gap-2 w-full">
                                                        📄 Unduh SPK
                                                    </a>
                                                </div>
                                            @elseif(($bid->tender->status ?? '') == 'open' && $bid->status != 'rejected')
                                                <button 
                                                    @click="
                                                        showEditModal = true; 
                                                        editTenderId = '{{ $bid->tender_id }}';
                                                        editTenderTitle = '{{ addslashes($bid->tender->title) }}';
                                                        editPrice = '{{ (float) $bid->getDecryptedPrice() }}';
                                                    "
                                                    class="bg-white border-2 border-indigo-600 text-indigo-600 px-6 py-2.5 rounded-xl text-[10px] font-black hover:bg-indigo-600 hover:text-white transition-all shadow-sm active:scale-95 tracking-widest uppercase w-full">
                                                    ✏️ Edit Penawaran
                                                </button>
                                            @else
                                                <span class="text-xs text-gray-400 italic font-medium">Tertutup / Final</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-16 text-center">
                                            <div class="inline-block p-6 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                                                <p class="text-gray-500 font-black uppercase tracking-widest text-xs italic">Belum Ada Riwayat Penawaran</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- EDIT MODAL (Alpine.js) --}}
        <div x-show="showEditModal" 
             style="display: none;" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
             
            <!-- Backdrop -->
            <div x-show="showEditModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity"></div>

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <!-- Modal Panel -->
                <div x-show="showEditModal"
                     @click.away="showEditModal = false"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-[3rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100 relative">
                    
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/10 rounded-full blur-[40px]"></div>

                    <div class="bg-white px-8 pt-10 pb-8 relative z-10">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-2xl bg-indigo-100 text-indigo-600 sm:mx-0 sm:h-12 sm:w-12 shadow-inner">
                                ✏️
                            </div>
                            <div class="mt-4 text-center sm:mt-0 sm:ml-6 sm:text-left w-full">
                                <h3 class="text-2xl leading-6 font-black text-gray-900 tracking-tight mb-2" id="modal-title">
                                    Revisi Penawaran
                                </h3>
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-6" x-text="editTenderTitle"></p>
                                
                                <form action="{{ route('bid.store') }}" method="POST" id="editBidForm">
                                    @csrf
                                    <!-- Tender ID tersembunyi, kita pakai nilai dari baris yang diklik -->
                                    <input type="hidden" name="tender_id" x-model="editTenderId">
                                    
                                    <div class="mt-2">
                                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Harga Penawaran Baru (Rp)</label>
                                        <div class="relative">
                                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-indigo-600 font-black">Rp</span>
                                            <input type="number" name="offered_price" x-model="editPrice" class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-14 pr-6 focus:ring-2 focus:ring-indigo-500 text-lg font-black text-gray-900" required>
                                        </div>
                                        <p class="text-[10px] text-indigo-500 mt-3 font-medium italic">Data baru akan ditimpa (updateOrCreate) dan otomatis dienkripsi dengan AES-256-CBC.</p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-8 py-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" form="editBidForm" class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-lg px-6 py-4 bg-indigo-600 text-sm font-black text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-xs uppercase tracking-widest transition-all active:scale-95">
                            Simpan & Enkripsi
                        </button>
                        <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-2xl border-2 border-gray-200 px-6 py-4 bg-white text-sm font-black text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-xs uppercase tracking-widest transition-all active:scale-95">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
