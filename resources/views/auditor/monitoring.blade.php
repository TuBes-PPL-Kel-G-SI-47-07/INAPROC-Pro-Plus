<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Auditor Monitoring Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- WIDGETS RINGKASAN --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center items-center hover:shadow-md transition duration-300">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-2">Tender Berjalan</div>
                    <div class="text-4xl font-bold text-blue-600">{{ $totalRunningTenders }}</div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center items-center hover:shadow-md transition duration-300">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-2">Tender Butuh Survey</div>
                    <div class="text-4xl font-bold text-yellow-500">{{ $totalSurveyNeeded }}</div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center items-center hover:shadow-md transition duration-300">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-2">Tender Selesai</div>
                    <div class="text-4xl font-bold text-green-600">{{ $totalCompletedTenders }}</div>
                </div>
            </div>

            {{-- TABEL PEMANTAUAN UTAMA --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Pemantauan Paket Pengadaan</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-medium border-b border-gray-100">Nama Paket Pengadaan</th>
                                <th class="px-6 py-4 font-medium border-b border-gray-100">Pagu Anggaran</th>
                                <th class="px-6 py-4 font-medium border-b border-gray-100">Jumlah Bids</th>
                                <th class="px-6 py-4 font-medium border-b border-gray-100">Status Progres</th>
                                <th class="px-6 py-4 font-medium border-b border-gray-100">Aksi Cepat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($procurements as $req)
                                <tr class="hover:bg-gray-50/50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $req->item_name }}</div>
                                        <div class="text-sm text-gray-500">PR-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">Rp {{ number_format($req->budget->nominal_awal ?? $req->total_price, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-sm">
                                            {{ $req->tender ? $req->tender->bids_count : 0 }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($req->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Pending Approval</span>
                                        @elseif($req->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                                        @elseif($req->status === 'approved' && !$req->tender)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu Tender</span>
                                        @elseif($req->tender)
                                            @if($req->tender->status === 'open')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Tender Open</span>
                                            @elseif($req->tender->status === 'closed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Tender Closed</span>
                                            @elseif($req->tender->status === 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Completed</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($req->tender->status) }}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($req->tender)
                                            <a href="{{ url('/dashboard?tender_id=' . $req->tender->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition duration-200">
                                                Detail Tender
                                            </a>
                                        @else
                                            <a href="{{ route('procurement.index') }}" class="text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition duration-200">
                                                Lihat PR
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada data pengadaan atau tender.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
