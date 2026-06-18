# 📋 REQUIREMENTS & ACCEPTANCE CRITERIA PBI (1 - 21)
**Project:** INAPROC+ Enterprise E-Procurement

[cite_start]Dokumen ini mendefinisikan *Acceptance Criteria* (Kriteria Selesai) dan deskripsi teknis untuk 21 Product Backlog Items (PBI) yang terbagi dalam 3 Sprint pengembangan[cite: 685].

---

## 🏃 SPRINT 1: Foundation & Resource Planning
[cite_start]Fokus: Fondasi sistem, keamanan akses, dan manajemen anggaran[cite: 685].

* [cite_start]**PBI-01: Sistem Autentikasi & Authorization (RBAC)** [cite: 673]
    * *Aktor:* Semua Aktor
    * [cite_start]*Kriteria:* Sistem menggunakan Laravel Middleware untuk membatasi akses berdasarkan peran (`admin`, `vendor`, `auditor`, `pemohon`)[cite: 499, 504]. [cite_start]Autentikasi password menggunakan enkripsi Bcrypt[cite: 509].
* [cite_start]**PBI-02: Manajemen Profil & Keamanan Akun** [cite: 673]
    * *Aktor:* Semua Aktor
    * *Kriteria:* Pengguna dapat mengupdate data diri (nomor telepon, alamat, foto profil). [cite_start]Khusus Vendor, akun awalnya berstatus `unverified` hingga disetujui[cite: 454].
* [cite_start]**PBI-03: Konfigurasi Master Data Unit & Departemen** [cite: 673]
    * *Aktor:* Admin
    * *Kriteria:* Admin dapat menambah, mengedit, dan menghapus data unit kerja yang akan melakukan pengadaan barang.
* [cite_start]**PBI-04: Alokasi & Inisialisasi Pagu Anggaran Tahunan** [cite: 673]
    * *Aktor:* Admin
    * *Kriteria:* Admin dapat memasukkan total anggaran per unit (`nominal_awal`). Sistem otomatis melacak `sisa_pagu` yang tersedia[cite: 608].
* [cite_start]**PBI-05: Master Data Barang & Jasa (Standard Price)** [cite: 673]
    * *Aktor:* Admin
    * [cite_start]*Kriteria:* CRUD data barang standar yang menjadi acuan harga (*base price*) untuk mencegah *mark-up* harga yang tidak wajar[cite: 613].
* [cite_start]**PBI-06: Form Pengajuan Pengadaan (Purchase Requisition)** [cite: 673]
    * *Aktor:* Pemohon
    * *Kriteria:* Unit kerja dapat menginput item, kuantitas, dan estimasi harga. [cite_start]Status awal adalah `pending`[cite: 642].
* [cite_start]**PBI-07: Modul Unggah Dokumen TOR/KAK** [cite: 673]
    * *Aktor:* Pemohon
    * [cite_start]*Kriteria:* Pemohon wajib mengunggah file pendukung (Kerangka Acuan Kerja/Term of Reference) berformat PDF (Maks 5MB)[cite: 509].
* [cite_start]**PBI-08: Smart Budgeting: Auto-Check Saldo Pagu** [cite: 673]
    * *Aktor:* Sistem/Admin
    * [cite_start]*Kriteria:* Sistem otomatis memblokir/menolak pengajuan jika estimasi total nilai pengadaan melebihi `sisa_pagu` pada tabel `budgets`[cite: 609].

---

## 🏃 SPRINT 2: Core Smart Auction Engine
[cite_start]Fokus: Siklus lelang cerdas, validasi vendor, dan algoritma penentuan pemenang[cite: 685].

* [cite_start]**PBI-09: Workflow Persetujuan & Validasi Admin** [cite: 673]
    * *Aktor:* Admin
    * [cite_start]*Kriteria:* Admin meninjau PR (Purchase Requisition) dari Pemohon dan mengubah statusnya menjadi `approved` atau `rejected`[cite: 642].
* [cite_start]**PBI-10: Publikasi Paket Tender ke Portal Vendor** [cite: 673]
    * *Aktor:* Admin
    * *Kriteria:* PR yang disetujui diubah menjadi paket `tenders`. [cite_start]Admin menentukan rentang waktu lelang (`start_date` & `end_date`)[cite: 466].
* [cite_start]**PBI-11: EVP: Perizinan & Pinpoint Geotagging Kantor** [cite: 673]
    * *Aktor:* Vendor
    * *Kriteria:* Vendor memvalidasi domisili fisik kantor menggunakan integrasi Leaflet.js/OpenStreetMap. [cite_start]Mencegah pendaftaran "Vendor Hantu"[cite: 127, 513].
* [cite_start]**PBI-12: Sealed Bidding: Form Input Harga Terenkripsi** [cite: 673]
    * *Aktor:* Vendor
    * [cite_start]*Kriteria:* Nilai penawaran harga vendor (`offered_price`) dienkripsi oleh sistem di database dan TIDAK BISA dilihat oleh siapapun sebelum waktu lelang habis[cite: 335, 617].
* [cite_start]**PBI-13: Modul Portofolio & Dokumen Penawaran Teknis** [cite: 673]
    * *Aktor:* Vendor
    * *Kriteria:* Vendor mengunggah bukti multimedia (foto/video asli) sebagai syarat teknis. [cite_start]Sistem menyimpan *file path*-nya secara aman[cite: 329].
* [cite_start]**PBI-14: Dashboard Monitoring Status Real-time** [cite: 673]
    * *Aktor:* Pemohon/Vendor
    * [cite_start]*Kriteria:* Halaman ringkasan yang menampilkan metrik status tender aktif (Open, Closed, Completed)[cite: 660].
* [cite_start]**PBI-15: DSS Engine: Algoritma Auto-Scoring Seleksi** [cite: 673]
    * *Aktor:* Admin / Sistem
    * *Kriteria:* Setelah tender ditutup, sistem otomatis melakukan dekripsi harga dan menghitung skor akhir (harga, teknis, integritas) berdasarkan bobot. [cite_start]Sistem menghasilkan *Matriks Komparasi*[cite: 343, 637].
* [cite_start]**PBI-16: Penerbitan SPK & Kontrak Digital (Auto-Generate)** [cite: 673]
    * *Aktor:* Admin
    * [cite_start]*Kriteria:* Jika pemenang telah ditetapkan, sistem otomatis men- *generate* dokumen Surat Perintah Kerja (SPK) dalam format PDF[cite: 469].

---

## 🏃 SPRINT 3: Monitoring, Forensic Audit & Reporting
[cite_start]Fokus: Pengawasan pasca-lelang, pelaporan progres, dan rekam jejak audit[cite: 685].

* [cite_start]**PBI-17: Visual Progress: Upload Bukti Geotagging** [cite: 673]
    * *Aktor:* Vendor
    * *Kriteria:* Vendor wajib mengunggah foto progres fisik pekerjaan. (🚨 *Catatan: Saat ini terdapat kendala saat merge, perlu debug lanjutan*).
* [cite_start]**PBI-18: Final Inspection & Modul Unggah BAST** [cite: 673]
    * *Aktor:* Pemohon/Vendor
    * *Kriteria:* Vendor mengunggah dokumen Berita Acara Serah Terima (BAST). [cite_start]Pemohon melakukan klik tombol verifikasi persetujuan barang diterima[cite: 499].
* [cite_start]**PBI-19: Dashboard Analitik & Fraud Detection (Charts)** [cite: 673]
    * *Aktor:* Auditor
    * [cite_start]*Kriteria:* Menampilkan visualisasi data (grafik efisiensi anggaran, tren pengadaan) menggunakan Chart.js/ApexCharts untuk mendeteksi anomali[cite: 236, 513].
* [cite_start]**PBI-20: Immutable Audit Trail Log (Activity Tracker)** [cite: 673]
    * *Aktor:* Auditor / Sistem
    * *Kriteria:* Setiap aksi CRUD (Create, Update, Delete) di sistem dicatat permanen ke tabel `activity_logs`. [cite_start]Data di tabel ini dikunci (tidak bisa dihapus/diedit oleh admin sekalipun) untuk menjaga integritas forensik (SDG 16)[cite: 229, 621].
* [cite_start]**PBI-21: Forensic Report Export (Format PDF Legal)** [cite: 673]
    * *Aktor:* Auditor
    * [cite_start]*Kriteria:* Auditor dapat mengekspor seluruh jejak digital (mulai dari *profiling*, harga *bidding*, skor, hingga log aktivitas) dari suatu paket tender menjadi satu dokumen laporan PDF yang siap dibawa ke meja persidangan atau audit formal[cite: 261, 396].