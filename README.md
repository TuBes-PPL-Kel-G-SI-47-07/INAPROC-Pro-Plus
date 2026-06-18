# 🏛️ INAPROC+ (Enterprise E-Procurement System)

> **"Integritas Berbasis Bukti Nyata"** > Sistem Pengadaan Barang dan Jasa Cerdas untuk mewujudkan Tata Kelola Pemerintahan yang Bersih, Akuntabel, dan Bebas dari Praktik Kolusi (Mendukung Target **SDG 16**).

---

## 📌 Tentang Proyek
[cite_start]INAPROC+ adalah *Super-App Enhancement* yang dirancang untuk merevolusi ekosistem pengadaan sektor publik[cite: 275]. [cite_start]Sistem ini menambal celah kritis pada portal pengadaan eksisting dengan menghilangkan fenomena "Vendor Hantu" (Perusahaan Cangkang), praktik pinjam bendera, dan manipulasi nilai tender oleh panitia[cite: 76, 81, 288].

Melalui INAPROC+, proses birokrasi yang kaku ditransformasikan menjadi alur digital tertutup yang aman, transparan, dan divalidasi secara faktual oleh mesin.

## ✨ Fitur Keunggulan (Enterprise Security)

* [cite_start]📍 **Enhanced Vendor Profiling (EVP):** Validasi eksistensi fisik vendor menggunakan *Geotagging* GPS dan bukti multimedia bermetadata untuk mencegah manipulasi profil[cite: 124, 127].
* [cite_start]🔒 **AES-256 Sealed Bidding:** Penawaran harga dari vendor dienkripsi secara absolut di tingkat *database* dan baru bisa diakses (*decrypted*) oleh sistem setelah waktu lelang resmi ditutup[cite: 215].
* [cite_start]🤖 **Decision Support System (Auto-Scoring):** Algoritma perhitungan nilai akhir secara otomatis (menggabungkan bobot Harga, Teknis, dan Integritas) untuk menghilangkan bias/intervensi manusia dalam penentuan pemenang[cite: 168, 169].
* [cite_start]🔗 **Immutable Audit Trail:** Log aktivitas sistem berkonsep *tamper-proof* (anti-ubah) menggunakan validasi rantai Hash SHA-256[cite: 228, 229]. [cite_start]Siap untuk audit forensik BPK/KPK[cite: 262].
* [cite_start]💸 **Smart Budgeting (Pagu Check):** Pengecekan saldo anggaran unit secara *real-time* yang otomatis menolak pengajuan jika melampaui batas fiskal[cite: 318].

## 🛠️ Tech Stack Utama

* [cite_start]**Backend:** Laravel 11.x (PHP 8.2+) [cite: 513]
* [cite_start]**Frontend:** Tailwind CSS, Alpine.js, Blade Templating [cite: 513]
* [cite_start]**Database:** MySQL 8.0 (Atomic Transactions) [cite: 513]
* [cite_start]**Multimedia/Geo:** Leaflet.js (Maps) & Browsershot (PDF Export) [cite: 513]

## 👥 Aktor (Role-Based Access)
1. **Admin:** Pusat kendali tender, anggaran, dan master data.
2. **Pemohon:** Unit kerja inisiator kebutuhan barang/jasa.
3. **Vendor:** Penyedia jasa tersertifikasi (Peserta Lelang).
4. **Auditor:** Pengawas independen tingkat tinggi (Read-Only & Field Survey).

## 🚀 Cara Instalasi (Local Development)

1. Clone repository ini: `git clone https://github.com/username/inaproc-plus.git`
2. Masuk ke direktori proyek: `cd inaproc-plus`
3. Install dependensi PHP: `composer install`
4. Install dependensi Node.js: `npm install && npm run build`
5. Salin file environment: `cp .env.example .env`
6. Generate App Key: `php artisan key:generate`
7. Konfigurasi database di file `.env`.
8. Jalankan migrasi dan seeder: `php artisan migrate --seed`
9. Jalankan local server: `php artisan serve`

---
*Dikembangkan dengan dedikasi tinggi oleh Firman Zuhdi Affandi & Muhammad Devara untuk memajukan integritas bangsa.*