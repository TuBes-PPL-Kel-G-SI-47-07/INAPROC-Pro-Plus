<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('procurement.index') }}" class="mr-4 text-slate-400 hover:text-blue-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
                    {{ __('Buat Pengajuan Pengadaan') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Isi formulir Smart Procurement di bawah ini.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-10 md:p-12 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
                    <svg class="w-32 h-32 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path></svg>
                </div>
                
                <h3 class="text-2xl font-black text-slate-800 flex items-center mb-8 tracking-tight">
                    <span class="mr-4 bg-indigo-50 p-3 rounded-xl shadow-inner text-indigo-600">📦</span> 
                    Formulir Smart Procurement Request
                </h3>

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow-sm mb-8" role="alert">
                        <p class="font-bold">Gagal!</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                <form action="{{ route('procurement.store') }}" method="POST" class="space-y-8 relative z-10">
                    @csrf
                    
                    <div class="p-6 bg-slate-50 rounded-xl border border-slate-200">
                        <x-input-label for="budget_id" :value="__('Gunakan Plafon Pagu (Budget Guard)')" class="font-bold text-slate-700 mb-3" />
                        <select name="budget_id" id="budget_id" class="mt-1 block w-full border-slate-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white font-semibold text-slate-800 shadow-sm" required>
                            <option value="">-- Pilih Anggaran Unit Kerja --</option>
                            @foreach($budgets as $budget)
                                <option value="{{ $budget->id }}">
                                    {{ $budget->nama_pagu }} (Sisa Pagu Tersedia: Rp {{ number_format($budget->sisa_pagu, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            Sistem akan menolak pengajuan jika melebihi sisa pagu.
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="item_name" class="font-bold text-slate-700 mb-2">Nama Barang / Jasa</x-input-label>
                            <input type="text" name="item_name" id="item_name" class="w-full border-slate-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Pengadaan Laptop Spesifikasi Teknis" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="quantity" class="font-bold text-slate-700 mb-2">Jumlah (Kuantitas)</x-input-label>
                                <input type="number" name="quantity" id="quantity" min="1" class="w-full border-slate-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="10" required>
                            </div>
                            <div>
                                <x-input-label for="price" class="font-bold text-slate-700 mb-2">Harga Satuan (Rp)</x-input-label>
                                <input type="number" name="price" id="price" min="0" class="w-full border-slate-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="15000000" required>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description" class="font-bold text-slate-700 mb-2">Spesifikasi Singkat / Deskripsi</x-input-label>
                            <textarea name="description" id="description" rows="3" class="w-full border-slate-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Jelaskan kebutuhan spesifik dari barang/jasa ini..."></textarea>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-8 rounded-xl font-black text-sm uppercase tracking-wider shadow-lg shadow-indigo-200 transition-all focus:ring-4 focus:ring-indigo-300 flex items-center">
                            Submit Request
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>