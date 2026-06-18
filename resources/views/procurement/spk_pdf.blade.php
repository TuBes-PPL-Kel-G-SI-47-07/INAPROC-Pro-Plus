<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Perintah Kerja (SPK)</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #222;
        }
        .kop-surat {
            width: 100%;
            border-bottom: 4px double #000;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .kop-surat table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-surat td {
            vertical-align: middle;
        }
        .logo {
            width: 80px;
            height: 80px;
            background-color: #ddd;
            text-align: center;
            line-height: 80px;
            font-weight: bold;
            color: #555;
            border-radius: 50%;
        }
        .instansi {
            text-align: center;
        }
        .instansi h1 {
            font-size: 22px;
            margin: 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .instansi p {
            margin: 2px 0;
            font-size: 12px;
        }
        .nomor-surat {
            text-align: center;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .nomor-surat .judul {
            font-size: 18px;
            text-decoration: underline;
            margin-bottom: 5px;
            display: inline-block;
        }
        .content {
            margin-bottom: 30px;
            text-align: justify;
        }
        table.info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        table.info-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        table.tabel-barang {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 30px;
        }
        table.tabel-barang th, table.tabel-barang td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }
        table.tabel-barang th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .tanda-tangan {
            width: 100%;
            margin-top: 50px;
        }
        .tanda-tangan td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            height: 120px;
        }
        .signature-line {
            display: inline-block;
            width: 200px;
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <table>
            <tr>
                <td style="width: 15%;">
                    <div class="logo">LOGO</div>
                </td>
                <td style="width: 70%;" class="instansi">
                    <h1>PEMERINTAH REPUBLIK INDONESIA</h1>
                    <h2>KEMENTERIAN PENGADAAN DIGITAL (INAPROC+)</h2>
                    <p>Gedung Teknologi Cerdas Lantai 5, Jl. Inovasi No. 10, Jakarta Pusat 10110</p>
                    <p>Telepon: (021) 1234567 | Faksimili: (021) 7654321 | Email: pengadaan@inaproc.go.id</p>
                </td>
                <td style="width: 15%;"></td>
            </tr>
        </table>
    </div>

    <!-- Nomor Surat -->
    <div class="nomor-surat">
        <div class="judul">SURAT PERINTAH KERJA (SPK)</div>
        <div style="font-weight: normal;">Nomor: SPK-{{ date('Y/m') }}/INP/{{ str_pad($procurement->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <!-- Isi Surat -->
    <div class="content">
        <p>Pada hari ini, tanggal <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</strong>, yang bertanda tangan di bawah ini sepakat untuk menerbitkan Surat Perintah Kerja (SPK) untuk pengadaan barang/jasa dengan detail sebagai berikut:</p>
        
        <table class="info-table">
            <tr>
                <td style="width: 30%;"><strong>Nama Vendor / Pelaksana</strong></td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%;"><strong>{{ $procurement->vendor->name ?? ($procurement->user->name ?? 'Vendor') }}</strong></td>
            </tr>
            <tr>
                <td><strong>Status Akun Vendor</strong></td>
                <td>:</td>
                <td>{{ ucfirst($procurement->vendor->status ?? ($procurement->user->status ?? 'Active')) }}</td>
            </tr>
            <tr>
                <td><strong>Sumber Dana / Pagu</strong></td>
                <td>:</td>
                <td>{{ $procurement->budget->nama_pagu }}</td>
            </tr>
            <tr>
                <td><strong>Keterangan Pekerjaan</strong></td>
                <td>:</td>
                <td>{{ $procurement->description ?? 'Pengadaan operasional standar instansi.' }}</td>
            </tr>
        </table>

        <p>Adapun rincian item pengadaan yang diinstruksikan dalam SPK ini adalah:</p>
        
        <table class="tabel-barang">
            <thead>
                <tr>
                    <th style="width: 8%;">No.</th>
                    <th style="width: 42%;">Nama Barang / Jasa</th>
                    <th style="width: 15%;">Kuantitas</th>
                    <th style="width: 35%;">Total Biaya (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>{{ $procurement->item_name }}</td>
                    <td style="text-align: center;">{{ number_format($procurement->quantity, 0, ',', '.') }} Unit</td>
                    <td style="text-align: right;"><strong>Rp {{ number_format(isset($winnerBid) ? (float) $winnerBid->getDecryptedPrice() : $procurement->total_price, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: right;">GRAND TOTAL</th>
                    <th style="text-align: right;">Rp {{ number_format(isset($winnerBid) ? (float) $winnerBid->getDecryptedPrice() : $procurement->total_price, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <p>Demikian Surat Perintah Kerja ini dibuat dengan sesungguhnya dalam 2 (dua) rangkap untuk dapat dilaksanakan dengan penuh tanggung jawab, transparan, dan akuntabel sesuai dengan prinsip pengadaan INAPROC+.</p>
    </div>

    <!-- Tanda Tangan -->
    <table class="tanda-tangan">
        <tr>
            <td>
                Menerima Pekerjaan,<br>
                <strong>Pihak Vendor / Pelaksana</strong>
                <br><br><br><br><br>
                <div class="signature-line"></div><br>
                <strong>{{ strtoupper($procurement->vendor->name ?? ($procurement->user->name ?? 'VENDOR')) }}</strong><br>
                Direktur Utama
            </td>
            <td>
                Dikeluarkan di: Jakarta<br>
                Pada Tanggal: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                <strong>Pejabat Pembuat Komitmen (PPK)</strong>
                <br><br>
                <div style="margin: 0 auto; text-align: center; width: 75px; height: 75px;">
                    <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(75)->errorCorrection('H')->generate(url('/verify/spk/' . $procurement->uuid))) !!}" alt="QR Code Verification" style="width: 75px; height: 75px; display: block;">
                </div>
                <br>
                <div class="signature-line"></div><br>
                <strong>AUDITOR UTAMA</strong><br>
                NIP. 19800101 200501 1 001
            </td>
        </tr>
    </table>

</body>
</html>
