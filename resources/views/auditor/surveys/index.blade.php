<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel Verifikasi Auditor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="mb-4 font-bold">Daftar Laporan Survey Pending</h3>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <a href="{{ route('auditor.portfolios.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded mb-4 inline-block">Cek Portfolio Vendor</a>

                    <div class="overflow-x-auto bg-white rounded-lg shadow">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border p-2">id</th>
                                    <th class="border p-3">Nama Vendor</th>
                                    <th class="border p-3">Foto Lokasi</th>
                                    <th class="border p-3">Kondisi Kantor</th>
                                    <th class="border p-3">Skor</th>
                                    <th class="border p-3">Status</th>
                                    <th class="border p-3">Aksi</th>
                                    <th class="border p-3">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surveys as $survey)
                                <tr class="hover:bg-gray-50 text-center">
                                    <td class="border p-3 text-sm">{{ $survey->id }}</td>
                                    <td class="border p-3 text-sm font-semibold">{{ $survey->user->name ?? 'User Tidak Ditemukan' }}</td>
                                    <td class="border p-3">
                                        @if($survey->survey_photo)
                                            {{-- Kita bungkus dengan asset('storage/...') --}}
                                            <a href="{{ asset('storage/' . $survey->survey_photo) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $survey->survey_photo) }}" 
                                                    alt="Foto Survey" 
                                                    class="w-20 h-20 object-cover mx-auto rounded shadow-sm border"
                                                    onerror="this.onerror=null;this.src='https://placehold.co/100?text=Error+Path';">
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Tidak ada foto</span>
                                        @endif
                                    </td>
                                    <td class="border p-3 text-sm">{{ $survey->office_condition }}</td>
                                    <td class="border p-3 text-sm">{{ $survey->infrastructure_score }}</td>
                                    <td class="border p-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold 
                                            {{ $survey->status == 'approved' ? 'bg-green-100 text-green-700' : 
                                            ($survey->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ strtoupper($survey->status) }}
                                        </span>
                                    </td>
                                    <td class="border p-3">
                                        <div class="flex justify-center gap-2">
                                            <form action="{{ route('auditor.surveys.verify', $survey->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition">Approve</button>
                                            </form>

                                            <button onclick="openRejectModal({{ $survey->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition">
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                    <td class="border p-3 text-sm">{{ $survey->auditor_notes ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                            <h3 class="text-lg font-bold mb-4">Alasan Penolakan</h3>
                            <form id="rejectForm" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <textarea name="auditor_notes" class="w-full border p-2 rounded mb-4" rows="4" placeholder="Tulis alasan kenapa ditolak..." required></textarea>
                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="closeRejectModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Kirim Penolakan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        function openRejectModal(id) {
                            const modal = document.getElementById('rejectModal');
                            const form = document.getElementById('rejectForm');
                            // Update action URL secara dinamis
                            form.action = `/auditor/surveys/${id}/verify`; 
                            modal.classList.remove('hidden');
                        }

                        function closeRejectModal() {
                            document.getElementById('rejectModal').classList.add('hidden');
                        }
                    </script>

                    <!-- <table class="min-w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-2">id</th>
                                <th class="border p-2">office_condition</th>
                                <th class="border p-2">infrastructure_score</th>
                                <th class="border p-2">status</th>
                                <th class="border p-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($surveys as $survey)
                            <tr class="hover:bg-gray-50 text-center">
                                <td class="border p-3 text-sm">{{ $survey->id }}</td>
                                
                                <td class="border p-3 text-sm">{{ $survey->office_condition ?? 'N/A' }}</td>
                                
                                <td class="border p-3 text-sm">{{ $survey->infrastructure_score ?? '0' }}</td>
                                
                                <td class="border p-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold 
                                        {{ $survey->status == 'approved' ? 'bg-green-100 text-green-700' : 
                                        ($survey->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ strtoupper($survey->status) }}
                                    </span>
                                </td>

                                <td class="border p-3">
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('auditor.surveys.verify', $survey->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition">Approve</button>
                                        </form>

                                        <form action="{{ route('auditor.surveys.verify', $survey->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition">Reject</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table> -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>