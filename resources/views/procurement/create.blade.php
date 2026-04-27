<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pengajuan Pengadaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                {{-- Form ini harus mengarah ke store --}}
                <form action="{{ route('procurement.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Pilih Pagu Anggaran</label>
                        <select name="budget_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            @foreach($budgets as $budget)
                                <option value="{{ $budget->id }}">
                                    {{ $budget->nama_pagu }} (Sisa: Rp {{ number_format($budget->sisa_pagu, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Nama Barang</label>
                        <input type="text" name="item_name" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Jumlah (Quantity)</label>
                            <input type="number" name="quantity" min="1" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Harga Satuan</label>
                            <input type="number" name="price" min="0" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                        Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>