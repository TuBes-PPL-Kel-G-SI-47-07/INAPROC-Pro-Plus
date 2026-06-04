<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Audit Forensik - {{ $procurement->item_name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        @page {
            margin: 60px 50px;
        }
        .header {
            border-bottom: 3px double #1e293b;
            padding-bottom: 10px;
            margin-bottom: 25px;
            position: relative;
        }
        .header-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin: 0;
        }
        .header-subtitle {
            font-size: 10px;
            color: #475569;
            margin: 4px 0 0 0;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header-meta {
            text-align: right;
            position: absolute;
            right: 0;
            top: 0;
            font-size: 9px;
            color: #64748b;
            line-height: 1.3;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #f1f5f9;
            border-left: 4px solid #4f46e5;
            padding: 6px 10px;
            margin-top: 25px;
            margin-bottom: 12px;
            color: #0f172a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #f8fafc;
            font-weight: bold;
            text-align: left;
            padding: 8px 10px;
            border-bottom: 2px solid #e2e8f0;
            color: #334155;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
            color: #334155;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #e2e8f0;
        }
        .field-label {
            font-weight: bold;
            color: #475569;
            width: 30%;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            font-size: 8px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #b45309;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .badge-info {
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-bold {
            font-weight: bold;
        }
        .page-break {
            page-break-before: always;
        }
        .progress-gallery {
            margin-top: 15px;
        }
        .progress-item {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 15px;
            background-color: #ffffff;
        }
        .progress-img-container {
            text-align: center;
            margin-top: 8px;
            background-color: #f8fafc;
            padding: 10px;
            border-radius: 6px;
        }
        .progress-img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
        .meta-stamp {
            margin-top: 40px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 15px;
            font-size: 9px;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- Print Footer -->
    <div class="footer">
        Dokumen Laporan Audit Forensik INAPROC+ • Dicetak otomatis pada {{ now()->format('Y-m-d H:i:s') }} • Rahasia Negara / Internal Auditor Only
    </div>

    {{-- HEADER --}}
    <div class="header">
        <div class="header-title">INAPROC+ Forensic Audit Report</div>
        <div class="header-subtitle">Laporan Hasil Pemeriksaan Pengadaan Digital</div>
        <div class="header-meta">
            <strong>ID LAPORAN:</strong> REP-{{ str_pad($procurement->id, 6, '0', STR_PAD_LEFT) }}-{{ now()->format('Y') }}<br>
            <strong>TANGGAL CETAK:</strong> {{ now()->format('d M Y H:i T') }}<br>
            <strong>PEMERIKSA:</strong> {{ Auth::user()->name }} (Auditor)
        </div>
    </div>

    {{-- SECTION 1: INFORMASI UMUM --}}
    <div class="section-title">1. Informasi Umum Proyek & Anggaran</div>
    <table>
        <tbody>
            <tr>
                <td class="field-label">Nama Paket Jasa/Barang</td>
                <td>: <span class="text-bold">{{ $procurement->item_name }}</span></td>
                <td class="field-label">Status Proyek</td>
                <td>: 
                    @if($procurement->status === 'completed')
                        <span class="badge badge-success">Selesai (Completed)</span>
                    @elseif($procurement->status === 'approved')
                        <span class="badge badge-info">Disetujui (Approved)</span>
                    @elseif($procurement->status === 'rejected')
                        <span class="badge badge-danger">Ditolak (Rejected)</span>
                    @else
                        <span class="badge badge-warning">{{ $procurement->status }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="field-label">Kuantitas & Harga Satuan</td>
                <td>: {{ $procurement->quantity }} Unit x Rp {{ number_format($procurement->price, 0, ',', '.') }}</td>
                <td class="field-label">Pagu Anggaran Asal</td>
                <td>: {{ $procurement->budget->nama_pagu ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="field-label">Total Estimasi Awal</td>
                <td>: <span class="text-bold">Rp {{ number_format($procurement->total_price, 0, ',', '.') }}</span></td>
                <td class="field-label">Realisasi Nilai Kontrak</td>
                <td>: 
                    @php
                        $winnerBid = $procurement->tender ? $procurement->tender->bids()->where('status', 'winner')->first() : null;
                        $finalVal = 0;
                        if ($winnerBid) {
                            try {
                                $finalVal = (float) $winnerBid->getDecryptedPrice();
                            } catch(\Exception $e) {}
                        }
                    @endphp
                    @if($finalVal > 0)
                        <span class="text-bold text-success">Rp {{ number_format($finalVal, 0, ',', '.') }}</span>
                        <span style="font-size: 8px; color: #16a34a; font-weight: bold; display: block;">
                            (Hemat: Rp {{ number_format($procurement->total_price - $finalVal, 0, ',', '.') }} / {{ number_format((($procurement->total_price - $finalVal) / $procurement->total_price) * 100, 1) }}%)
                        </span>
                    @else
                        <span>Rp - (Belum Kontrak/Selesai)</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="field-label">Inisiator (Pemohon)</td>
                <td>: {{ $procurement->user->name ?? 'Unknown' }} ({{ $procurement->user->email ?? 'N/A' }})</td>
                <td class="field-label">Vendor Terpilih (Penyedia)</td>
                <td>: 
                    @if($procurement->vendor)
                        <span class="text-bold">{{ $procurement->vendor->name }}</span> ({{ $procurement->vendor->email }})
                    @else
                        <span>Belum Ada Pemenang</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    {{-- SECTION 2: PROSES TENDER & PENAWARAN --}}
    @if($procurement->tender)
        <div class="section-title">2. Evaluasi Tender Cerdas & Bidding (Sealed Bidding)</div>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>Vendor Peserta</th>
                    <th class="text-center">Skor Harga</th>
                    <th class="text-center">Skor Teknis</th>
                    <th class="text-center">Skor Integritas</th>
                    <th class="text-center">Skor Akhir (DSS)</th>
                    <th class="text-right">Harga Penawaran (Decrypted)</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($procurement->tender->bids as $bid)
                    <tr style="{{ $bid->status === 'winner' ? 'background-color: #f0fdf4; font-weight: bold;' : '' }}">
                        <td>
                            {{ $bid->user->name ?? 'Unknown' }}
                            @if($bid->user && $bid->user->latitude)
                                <span style="display: block; font-size: 7px; color: #64748b; font-family: monospace;">GPS: {{ $bid->user->latitude }}, {{ $bid->user->longitude }}</span>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($bid->score_harga, 2) }}</td>
                        <td class="text-center">{{ number_format($bid->score_teknis, 2) }}</td>
                        <td class="text-center">{{ number_format($bid->score_integritas, 2) }}</td>
                        <td class="text-center" style="color: #4f46e5;">{{ number_format($bid->final_score, 2) }}</td>
                        <td class="text-right">
                            @php
                                $decryptedPrice = 0;
                                try {
                                    $decryptedPrice = (float) $bid->getDecryptedPrice();
                                } catch(\Exception $e) {}
                            @endphp
                            Rp {{ number_format($decryptedPrice, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($bid->status === 'winner')
                                <span class="badge badge-success">Pemenang</span>
                            @else
                                <span class="badge badge-info">{{ $bid->status }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-slate-400 italic">Tidak ada penawaran masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    {{-- SECTION 3: SERAH TERIMA BAST --}}
    @if($procurement->bastSubmission)
        <div class="section-title">3. Dokumen Serah Terima Pekerjaan (BAST)</div>
        <table>
            <tbody>
                <tr>
                    <td class="field-label">Catatan Serah Terima Vendor</td>
                    <td>: {{ $procurement->bastSubmission->description ?? '-' }}</td>
                    <td class="field-label">Status Verifikasi Auditor</td>
                    <td>: 
                        @if($procurement->bastSubmission->status === 'approved')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($procurement->bastSubmission->status === 'rejected')
                            <span class="badge badge-danger">Ditolak</span>
                        @else
                            <span class="badge badge-warning">Tertunda (Pending)</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="field-label">Catatan Review Auditor</td>
                    <td>: {{ $procurement->bastSubmission->auditor_notes ?? '-' }}</td>
                    <td class="field-label">Status Verifikasi Pemohon</td>
                    <td>: 
                        @if($procurement->bastSubmission->pemohon_status === 'approved')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($procurement->bastSubmission->pemohon_status === 'rejected')
                            <span class="badge badge-danger">Ditolak</span>
                        @else
                            <span class="badge badge-warning">Tertunda (Pending)</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="field-label">Catatan Penerimaan Pemohon</td>
                    <td>: {{ $procurement->bastSubmission->pemohon_notes ?? '-' }}</td>
                    <td class="field-label">Tanggal Pengajuan</td>
                    <td>: {{ $procurement->bastSubmission->created_at->format('d M Y H:i T') }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    {{-- SECTION 4: GALERI PROGRESS VISUAL (PAGE BREAK FOR SPACE) --}}
    @if($procurement->progresses->count() > 0)
        <div class="page-break"></div>
        <div class="section-title">4. Laporan Progres Visual & Geotagging GPS</div>
        <div class="progress-gallery">
            @foreach($procurement->progresses as $progress)
                <div class="progress-item">
                    <table style="margin-bottom: 0;">
                        <tbody>
                            <tr>
                                <td style="width: 25%; font-weight: bold; border-bottom: 0; padding: 0 5px 0 0;">Progres: {{ $progress->percentage }}%</td>
                                <td style="width: 25%; border-bottom: 0; padding: 0 5px 0 0;">Status: 
                                    @if($progress->status === 'approved')
                                        <span class="badge badge-success">Valid</span>
                                    @elseif($progress->status === 'anomaly')
                                        <span class="badge badge-danger">Anomali EXIF</span>
                                    @else
                                        <span class="badge badge-warning">{{ $progress->status }}</span>
                                    @endif
                                </td>
                                <td style="width: 50%; border-bottom: 0; padding: 0; text-align: right; font-size: 8px; color: #64748b;">
                                    Upload: {{ $progress->created_at->format('d M Y H:i T') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border-bottom: 0; padding: 8px 0 0 0; color: #475569; font-style: italic;">
                                    Deskripsi: "{{ $progress->description }}"
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border-bottom: 0; padding: 4px 0 0 0; font-family: monospace; font-size: 8px; color: #4f46e5;">
                                    Jejak GPS EXIF: 
                                    @if($progress->latitude && $progress->longitude)
                                        Latitude: {{ $progress->latitude }}, Longitude: {{ $progress->longitude }}
                                        @if($progress->taken_at)
                                             | Pengambilan Foto: {{ $progress->taken_at }}
                                        @endif
                                    @else
                                        <span style="color: #b91c1c; font-weight: bold;">(TIDAK MEMILIKI GEOTAG / DATA EXIF FOTO MANUAL)</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @if(isset($imagesBase64[$progress->id]))
                        <div class="progress-img-container">
                            <img src="{{ $imagesBase64[$progress->id] }}" class="progress-img">
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- SECTION 5: AUDIT TRAIL LOGS --}}
    <div class="page-break"></div>
    <div class="section-title">5. Immutable Audit Trail (Jejak Log Forensik)</div>
    <p style="font-size: 9px; color: #64748b; margin-bottom: 10px; font-style: italic;">Berikut adalah potongan log transaksi digital yang dicatat secara otomatis oleh mesin log imutabel sistem berkaitan dengan paket pengadaan ini:</p>
    <table class="table-bordered" style="font-size: 9px;">
        <thead>
            <tr>
                <th style="width: 20%;">Waktu Kejadian</th>
                <th style="width: 20%;">Pelaku (Aktor)</th>
                <th style="width: 15%;">Aksi</th>
                <th style="width: 15%;">Alamat IP</th>
                <th style="width: 30%;">Keterangan Perubahan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activityLogs as $log)
                <tr>
                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>
                        <span class="text-bold">{{ $log->user->name ?? 'System' }}</span>
                        <span style="display: block; font-size: 7px; color: #94a3b8; text-transform: uppercase;">
                            {{ $log->user ? $log->user->roles->pluck('name')->first() : 'System' }}
                        </span>
                    </td>
                    <td><span class="badge badge-info" style="font-size: 7px;">{{ $log->action }}</span></td>
                    <td class="font-mono text-center">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                    <td>{{ $log->description }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-slate-400 italic">Tidak ada catatan log audit trail khusus paket ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- STAMPS AND DISCLAIMERS --}}
    <div class="meta-stamp">
        <strong>INTEGRITY STAMP AND DISCLAIMER:</strong><br>
        Laporan ini diproduksi secara langsung dan otomatis oleh sistem penjamin integritas INAPROC+. Seluruh riwayat bidding dan audit trail log dilindungi oleh kendali enkripsi kriptografis dan tergolong <em>immutable</em> (tidak dapat diubah/dihapus secara sistem). Dokumen ini valid digunakan sebagai barang bukti digital formal untuk pelaporan hukum atau audit keuangan negara (BPK/KPK).
    </div>

</body>
</html>
