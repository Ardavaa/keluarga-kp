# Dashboard Riset Dosen FIF — `keluarga-kp`

Dashboard Laravel untuk memetakan topik riset, peta keahlian, dan potensi kolaborasi dosen Fakultas Informatika Telkom University, dibangun untuk KP Kelompok 1 (Syahdan Rizqi Ruhendy, Muhammad Ghozy Abdurrahman, Muhammad Karov Ardava Barus).

Dokumen perencanaan lengkap ada di:
- [`docs/PRD.md`](docs/PRD.md) — kebutuhan produk, arsitektur data, tech stack, referensi desain.
- [`docs/ROADMAP.md`](docs/ROADMAP.md) — rencana pengerjaan bertahap (sub-fase per sub-fase) beserar progresnya.

## Kebutuhan

- PHP **8.3+** dengan ekstensi: `pdo_pgsql`, `pgsql`, `mbstring`, `openssl`, `curl`, `fileinfo`, `zip`, `gd`, `intl` (semua sudah cukup untuk kebutuhan dashboard ini + koneksi PostgreSQL di masa depan)
- Composer **2.x**
- Node.js **LTS** + npm

Tidak ada asumsi path folder atau OS tertentu — proyek ini dikerjakan oleh lebih dari satu orang, jadi semua konfigurasi lewat `.env` (bukan hardcode).

## Setup

```bash
git clone https://github.com/Ardavaa/keluarga-kp.git
cd keluarga-kp

composer install

cp .env.example .env
php artisan key:generate

npm install
npm run build   # atau `npm run dev` untuk mode watch selama development

php artisan migrate

php artisan serve
```

Buka `http://127.0.0.1:8000`.

> Catatan data: saat ini `.env.example` masih memakai `DB_CONNECTION=sqlite` sebagai data lokal sementara (lihat `docs/ROADMAP.md` Fase 3). Koneksi ke database PostgreSQL bersama tim (hasil scraping dosen) baru disambungkan di Fase 7 setelah mekanisme integrasinya disepakati — lihat `docs/PRD.md` §4 dan §11.

## Struktur dokumentasi

```
docs/
├── PRD.md       # Product Requirement Document
└── ROADMAP.md   # Roadmap & progress tracker (checklist per sub-fase)
```

## Stack

Laravel 13 · Tailwind CSS v4 (bawaan skeleton, via `@tailwindcss/vite`) · Alpine.js · Vite — detail lengkap & alasan pemilihan ada di `docs/PRD.md` §8.
