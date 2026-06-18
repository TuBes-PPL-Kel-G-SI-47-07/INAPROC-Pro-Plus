<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi SPK Digital - INAPROC+</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: rgba(30, 41, 59, 0.4);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --valid-green: #10b981;
            --valid-glow: rgba(16, 185, 129, 0.2);
            --invalid-red: #ef4444;
            --invalid-glow: rgba(239, 68, 68, 0.2);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 10% 20%, rgba(16, 185, 129, 0.05) 0px, transparent 50%),
                radial-gradient(at 90% 80%, rgba(99, 102, 241, 0.05) 0px, transparent 50%);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            overflow-x: hidden;
        }

        .container {
            width: 100%;
            max-width: 600px;
            z-index: 10;
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: {{ $isValid ? 'linear-gradient(90deg, #10b981, #34d399)' : 'linear-gradient(90deg, #ef4444, #f87171)' }};
        }

        /* Status Icon and Glow */
        .status-badge-wrapper {
            margin-bottom: 2rem;
            position: relative;
            display: inline-block;
        }

        .status-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 2.5rem;
            position: relative;
            animation: pulse-glow 2s infinite ease-in-out;
        }

        .status-icon.valid {
            background: rgba(16, 185, 129, 0.1);
            border: 2px solid var(--valid-green);
            color: var(--valid-green);
            box-shadow: 0 0 20px var(--valid-glow);
        }

        .status-icon.invalid {
            background: rgba(239, 68, 68, 0.1);
            border: 2px solid var(--invalid-red);
            color: var(--invalid-red);
            box-shadow: 0 0 20px var(--invalid-glow);
        }

        @keyframes pulse-glow {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 15px rgba(16, 185, 129, 0.1);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 30px {{ $isValid ? 'rgba(16, 185, 129, 0.4)' : 'rgba(239, 68, 68, 0.4)' }};
            }
        }

        /* Title styling */
        h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        h1.valid {
            color: var(--valid-green);
            text-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
        }

        h1.invalid {
            color: var(--invalid-red);
            text-shadow: 0 0 15px rgba(239, 68, 68, 0.2);
        }

        .subtitle {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        /* Information Details List */
        .detail-group {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: left;
            margin-bottom: 2rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .detail-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .detail-row:first-child {
            padding-top: 0;
        }

        .detail-label {
            color: var(--text-secondary);
            font-weight: 400;
            font-size: 0.9rem;
        }

        .detail-value {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.95rem;
            text-align: right;
            max-width: 60%;
        }

        /* Button styles */
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.01) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            padding: 0.8rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Brand Footer */
        .footer-brand {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-secondary);
            letter-spacing: 0.5px;
        }

        .footer-brand strong {
            color: var(--text-primary);
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            
            @if($isValid)
                <!-- Valid State -->
                <div class="status-badge-wrapper">
                    <div class="status-icon valid">
                        ✓
                    </div>
                </div>
                
                <h1 class="valid">DOKUMEN VALID & TERVERIFIKASI DIGITAL</h1>
                <p class="subtitle">Surat Perintah Kerja (SPK) ini diterbitkan secara sah oleh Kementerian Pengadaan Digital (INAPROC+)</p>
                
                <div class="detail-group">
                    <div class="detail-row">
                        <span class="detail-label">Nomor SPK</span>
                        <span class="detail-value">SPK-{{ \Carbon\Carbon::parse($procurement->created_at)->format('Y/m') }}/INP/{{ str_pad($procurement->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Nama Pekerjaan</span>
                        <span class="detail-value">{{ $procurement->item_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Pihak Vendor / Pelaksana</span>
                        <span class="detail-value">{{ $procurement->vendor->name ?? 'Tidak Teridentifikasi' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Nilai Pekerjaan</span>
                        <span class="detail-value">Rp {{ number_format(isset($winnerBid) ? (float) $winnerBid->getDecryptedPrice() : $procurement->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Sumber Dana / Pagu</span>
                        <span class="detail-value">{{ $procurement->budget->nama_pagu ?? 'APBN' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Pejabat Penandatangan</span>
                        <span class="detail-value">AUDITOR UTAMA (PPK)</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Tanggal Validasi</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($procurement->updated_at)->translatedFormat('d F Y H:i') }} WIB</span>
                    </div>
                </div>
            @else
                <!-- Invalid State -->
                <div class="status-badge-wrapper">
                    <div class="status-icon invalid">
                        ✕
                    </div>
                </div>
                
                <h1 class="invalid">DOKUMEN TIDAK VALID</h1>
                <p class="subtitle">Dokumen tidak ditemukan atau status verifikasi digital tidak sah. Harap hubungi pihak berwenang.</p>
                
                <div class="detail-group" style="text-align: center; color: var(--text-secondary); font-size: 0.95rem; padding: 2rem;">
                    Peringatan: Tanda tangan digital atau QR Code pada dokumen ini tidak terdaftar di database sistem INAPROC+. Kemungkinan dokumen telah dimanipulasi atau dibatalkan.
                </div>
            @endif

            <a href="/" class="btn">Kembali ke Portal</a>
            
        </div>
        
        <div class="footer-brand">
            Sistem Verifikasi Digital <strong>INAPROC+</strong> © {{ date('Y') }}
        </div>
    </div>

</body>
</html>
