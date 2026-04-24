<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Portfolio Vendor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold">Kumpulan Portfolio Seluruh Vendor</h3>
                    <a href="{{ route('auditor.surveys.index') }}" class="text-blue-600 hover:underline">← Kembali ke Verifikasi Survey</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-center">
                                <th class="border p-3">Nama Vendor</th>
                                <th class="border p-3">Judul Portfolio</th>
                                <th class="border p-3">Deskripsi</th>
                                <th class="border p-3">File/Link</th>
                                <th class="border p-3">Tanggal Unggah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($portfolios as $portfolio)
                            <tr class="hover:bg-gray-50 text-center">
                                <td class="border p-3 text-sm font-semibold">{{ $portfolio->user->name ?? 'N/A' }}</td>
                                <td class="border p-3 text-sm">{{ $portfolio->title }}</td>
                                <td class="border p-3 text-sm text-left">{{ Str::limit($portfolio->description, 50) }}</td>
                                <td class="border p-3 text-sm">
                                    <a href="{{ asset('storage/' . $portfolio->file_path) }}" target="_blank" class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                                        Lihat Dokumen
                                    </a>
                                </td>
                                <td class="border p-3 text-sm">{{ $portfolio->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center p-4">Belum ada portfolio yang diunggah oleh vendor manapun.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>