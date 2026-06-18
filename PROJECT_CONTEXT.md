# 🧭 PROJECT CONTEXT: INAPROC+ (Enterprise E-Procurement)

## 📌 Visi Proyek (INAPROC vs INAPROC+)
[cite_start]INAPROC+ bukanlah sekadar aplikasi pengganti, melainkan **Penguatan Infrastruktur Digital (Super-App Enhancement)**[cite: 275]. [cite_start]Sistem ini dirancang untuk menambal celah korupsi, "vendor hantu", dan manipulasi data pada pengadaan konvensional[cite: 76, 111, 286]. 

**Terobosan Utama:**
1. [cite_start]**Enhanced Vendor Profiling (EVP):** Verifikasi eksistensi vendor menggunakan titik koordinat GPS (Geotagging) [cite: 127] [cite_start]dan bukti visual autentik[cite: 131].
2. [cite_start]**Sealed Bidding Encryption:** Harga penawaran vendor dienkripsi (AES/Bcrypt) dan hanya terbuka setelah lelang ditutup[cite: 215].
3. [cite_start]**Automated Scoring Engine:** Pemilihan pemenang murni berbasis data (Harga, Teknis, Integritas) tanpa intervensi manusia[cite: 289, 291].
4. [cite_start]**Immutable Audit Trail:** Catatan log aktivitas tidak dapat diubah/dihapus, menjamin audit forensik yang aman (SDG 16)[cite: 228, 229, 300].

---

## 👥 Aktor & Hak Akses (RBAC)
1. [cite_start]**Admin / Panitia:** Mengelola pagu, membuka lelang, dan menetapkan pemenang berdasarkan sistem[cite: 533, 534].
2. [cite_start]**Pemohon (Unit Kerja):** Mengajukan kebutuhan (PR) dan melakukan verifikasi BAST[cite: 536, 537].
3. [cite_start]**Vendor:** Melakukan pinpoint GPS kantor, unggah portofolio visual, dan mengirim penawaran (Sealed Bid)[cite: 539].
4. [cite_start]**Auditor:** Pengawas independen dengan akses *read-only* ke log aktivitas dan wewenang inspeksi lapangan[cite: 540, 541].

---

## 🚀 Status Product Backlog Items (PBI)
*Catatan Status: 🟢 (Done) | 🔴 (Issue/Conflict) | ⚪ (To-Do)*

**SPRINT 1: Foundation & Resource Planning**
* [cite_start]🟢 PBI-01: Sistem Autentikasi & Authorization (RBAC) 
* [cite_start]🟢 PBI-02: Manajemen Profil & Keamanan Akun 
* [cite_start]🟢 PBI-03: Konfigurasi Master Data Unit & Departemen 
* [cite_start]🟢 PBI-04: Alokasi & Inisialisasi Pagu Anggaran Tahunan 
* [cite_start]🟢 PBI-05: Master Data Barang & Jasa (Standard Price) 
* [cite_start]🟢 PBI-06: Form Pengajuan Pengadaan (Purchase Requisition) 
* [cite_start]🟢 PBI-07: Modul Unggah Dokumen TOR/KAK 
* [cite_start]🟢 PBI-08: Smart Budgeting: Auto-Check Saldo Pagu 

**SPRINT 2: Core Smart Auction Engine**
* [cite_start]🟢 PBI-09: Workflow Persetujuan & Validasi Admin 
* [cite_start]🟢 PBI-10: Publikasi Paket Tender ke Portal Vendor 
* [cite_start]🟢 PBI-11: EVP: Perizinan & Pinpoint Geotagging Kantor 
* [cite_start]🟢 PBI-12: Sealed Bidding: Form Input Harga Terenkripsi 
* [cite_start]🟢 PBI-13: Modul Portofolio & Dokumen Penawaran Teknis 
* [cite_start]🟢 PBI-14: Dashboard Monitoring Status Real-time 
* [cite_start]🟢 PBI-15: DSS Engine: Algoritma Auto-Scoring Seleksi 
* [cite_start]🟢 PBI-16: Penerbitan SPK & Kontrak Digital (Auto-Generate) 

**SPRINT 3: Monitoring, Forensic Audit & Reporting**
* [cite_start]🔴 PBI-17: Visual Progress: Upload Bukti Geotagging *(Status: Merge Conflict / Error)* 
* [cite_start]🟢 PBI-18: Final Inspection & Modul Unggah BAST 
* [cite_start]🟢 PBI-19: Dashboard Analitik & Fraud Detection (Charts) 
* [cite_start]🟢 PBI-20: Immutable Audit Trail Log (Activity Tracker) 
* [cite_start]⚪ PBI-21: Forensic Report Export (Format PDF Legal) 

**EXTRA FEATURE**
* 🟢 **UI/UX Enterprise Redesign:** Implementasi Tailwind CSS, Alpine.js, Landing Page, dan Glassmorphism (Selesai di Branch Terpisah).