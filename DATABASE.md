# 🗄️ DATABASE SCHEMA & RELATIONSHIPS
**Project:** INAPROC+ Enterprise E-Procurement

Dokumen ini adalah panduan arsitektur *database* utama. Dilarang mengubah nama kolom (halusinasi) saat melakukan *query* Eloquent.

---

## 1. 🛡️ AUTHENTICATION & RBAC (Spatie)
**`users`**
* Menyimpan entitas utama (Admin, Vendor, Auditor, Pemohon).
* `id` (PK)
* `name`, `email`, `password` (Bcrypt)
* `role_id` (FK ke tabel roles Spatie)
* `status` (Enum: `unverified`, `verified`, `rejected`) -> *Penting untuk validasi vendor (PBI-11).*

---

## 2. 💰 BUDGETING & REQUISITION (Fase 1)
**`budgets`** (Pagu Anggaran)
* `id` (PK)
* `nama_pagu` (String)
* `nominal_awal` (Decimal)
* `sisa_pagu` (Decimal) -> *Digunakan untuk validasi otomatis PBI-08.*

**`procurement_requests`** (Pengajuan / PR)
* `id` (PK)
* `user_id` (FK -> users) -> *Pemohon*
* `budget_id` (FK -> budgets)
* `vendor_id` (FK -> users) -> *Diisi otomatis jika pemenang sudah ditentukan.*
* `item_name`, `quantity` (Int), `price` (Decimal), `total_price` (Decimal)
* `status` (Enum: `pending`, `approved`, `rejected`)

---

## 3. ⚖️ TENDERING & SEALED BIDDING (Fase 2)
**`tenders`** (Paket Lelang)
* `id` (PK)
* `procurement_request_id` (FK -> procurement_requests)
* `title`, `description`
* `start_date`, `end_date` (Datetime)
* `status` (Enum: `open`, `closed`, `completed`)

**`tender_configs`** & **`procurement_files`**
* Menyimpan TOR/KAK dan bobot skoring (Harga, Teknis, Integritas). 

**`bids`** (Penawaran Vendor)
* `id` (PK)
* `tender_id` (FK -> tenders)
* `user_id` (FK -> users) -> *Vendor*
* `encrypted_price` (Text) -> *Data harga terenkripsi (PBI-12).*
* `score_harga`, `score_teknis`, `score_integritas`, `final_score` (Decimal) -> *Hasil Auto-Scoring (PBI-15).*
* `status` (Enum: `pending`, `sealed`, `rejected`, `winner`)

**`contracts`** (Penerbitan SPK - PBI 16)
* `id` (PK)
* `bid_id` (FK -> bids)
* `spk_number` (String)
* `contract_file_path` (String)

---

## 4. 🔍 AUDIT, MONITORING, & EVIDENCE (Fase 3)
**`portfolios`** (Bukti Visual Vendor)
* `id` (PK)
* `user_id` (FK -> users)
* `file_path`, `file_type` -> *Menyimpan foto/video asli vendor.*

**`survey_reports`** (Laporan Fisik/Progres Vendor)
* `id` (PK)
* `user_id` (FK -> users) -> *Vendor*
* `survey_photo` (String) -> *Foto progres fisik bermetadata (PBI-17).*
* `infrastructure_score` (Int)
* `status` (Enum: `pending`, `approved`, `rejected`)

**`activity_logs`** (Immutable Audit Trail - PBI 19/20)
* `id` (PK)
* `user_id` (FK -> users)
* `action` (String), `description` (Text)
* `previous_hash` (String) -> *Hash SHA-256 dari log sebelumnya (Blockchain concept).*
* `current_hash` (String) -> *Hash SHA-256 dari baris log saat ini.*