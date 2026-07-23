# Roadmap Pengerjaan — Dashboard `keluarga-kp`

Roadmap ini melengkapi [PRD.md](./PRD.md). Setiap fase dipecah jadi **sub-fase bernomor** (mis. `1.1`, `1.2`, ...) berisi langkah kecil dan konkret, supaya pengerjaan benar-benar bertahap — satu sub-fase = satu unit kerja yang bisa diselesaikan lalu dicentang, bukan satu fase besar sekaligus.

**Cara pakai:**
1. Kerjakan sub-fase berurutan di dalam satu fase (`1.1` → `1.2` → `1.3` ...). Antar fase besar juga berurutan, kecuali disebutkan bisa paralel.
2. Centang `- [ ]` → `- [x]` begitu task selesai.
3. Setelah semua sub-fase dalam satu fase tercentang, update kolom **Status** fase itu di tabel ringkasan jadi ✅ Selesai.
4. Kalau ada task baru muncul di tengah jalan, tambahkan sebagai sub-item baru di sub-fase yang relevan — jangan hapus histori centang yang sudah ada.
5. Commit & push ke GitHub dilakukan sendiri oleh pemilik repo — bukan bagian dari checklist roadmap ini.

---

## Ringkasan Progres

| Fase | Nama | Status |
|---|---|---|
| 0 | Persiapan & Riset | ✅ Selesai (5/5 sub-fase) |
| 1 | Inisialisasi Proyek Laravel | ✅ Selesai (5/5 sub-fase) |
| 2 | Layout & Komponen Dasar (Desain) | ✅ Selesai (6/6 sub-fase) |
| 3 | Data Layer Sementara (Migration + Seeder Dummy) | ✅ Selesai (5/5 sub-fase) |
| 4 | Halaman Inti | ✅ Selesai (4/4 sub-fase) |
| 5 | Visualisasi Lanjutan | ✅ Selesai (3/3 sub-fase) |
| 6 | Filter Global & Export | ✅ Selesai (3/3 sub-fase) |
| 7 | Integrasi Data Real (Scraper → MySQL) | ⬜ 0/5 sub-fase (menunggu prasyarat infra MySQL & penyesuaian repo scraper) — sudah ada trial import identitas dosen dari spreadsheet, lihat catatan di Fase 7 |
| 8 | Auth & Panel Admin | ✅ Selesai (4/4 sub-fase, pakai auth manual bukan Breeze — lihat catatan) |
| 9 | Polish, QA & Serah Terima | ⬜ 0/5 sub-fase |

Legenda status: ⬜ Belum mulai · 🔄 Sedang dikerjakan · ✅ Selesai · ⛔ Terblokir

---

## Fase 0 — Persiapan & Riset ✅

### 0.1 Riset sumber data
- [x] Pelajari struktur data & skema DB dari repo scraper (`Telkom-University-Lecturer-Scraper-and-Dashboard-Prototype`)

### 0.2 Riset kebutuhan tugas
- [x] Pelajari dokumen tugas KP (fitur wajib, halaman wajib)

### 0.3 Riset desain
- [x] Pelajari desain visual Telkom University (warna, tipografi, pola UI)

### 0.4 Dokumentasi perencanaan
- [x] Susun [PRD.md](./PRD.md)
- [x] Susun roadmap ini

### 0.5 Keputusan & housekeeping repo
- [x] Tambahkan `CLAUDE.md`/`AGENT.md`/`AGENTS.md`/`.claude/` ke `.gitignore` di kedua repo
- [x] Putuskan arsitektur data tingkat tinggi: **satu database MySQL** dipakai bersama scraper & dashboard, sumber data **Scopus**, koreksi Admin dengan penanda override per field (lihat `docs/PRD.md` §4)

---

## Fase 1 — Inisialisasi Proyek Laravel ✅

> Tujuan akhir fase: proyek Laravel kosong tapi bisa di-clone & dijalankan siapa pun di tim tanpa asumsi path/OS tertentu.

### 1.1 Scaffold project Laravel ✅
- [x] `composer create-project laravel/laravel .` di root `keluarga-kp` — versi stabil terbaru saat ini terpasang **Laravel 13.8+, PHP ^8.3** (bukan Laravel 11 seperti asumsi awal PRD — sudah dikoreksi ke Laravel 13.x di `docs/PRD.md` §8)
- [x] `php artisan serve` diverifikasi jalan, halaman welcome default merespons HTTP 200

### 1.2 Setup Tailwind CSS ✅
- [x] Tailwind CSS v4 ternyata **sudah bawaan skeleton Laravel 13** lewat plugin `@tailwindcss/vite` (bukan `tailwind.config.js` ala v3 — Tailwind v4 dikonfigurasi lewat blok `@theme` di `resources/css/app.css`). Tinggal `npm install`.
- [x] Verifikasi `npm run build` sukses menghasilkan `public/build/assets/app-*.css` berisi Tailwind

### 1.3 Setup Alpine.js ✅
- [x] Install Alpine.js via npm (`npm install alpinejs`)
- [x] Registrasi di `resources/js/app.js`
- [x] Verifikasi komponen `x-show`/`x-on:click` toggle berfungsi (dicek lewat browser preview: `display: none` → `inline` setelah klik)

### 1.4 Environment & konfigurasi ✅
- [x] `.env.example` disiapkan (APP_NAME disesuaikan, catatan DB sementara SQLite → MySQL saat integrasi Fase 7 ditambahkan sebagai komentar)
- [x] `.gitignore` bawaan Laravel digabung dengan entri custom yang sudah ada (`CLAUDE.md`/`AGENT.md`/`AGENTS.md`/`.claude/`)

### 1.5 Dokumentasi setup ✅
- [x] `README.md` ditulis ulang: kebutuhan tool, langkah setup clone → `composer install` → `.env` → `npm install` → `php artisan serve`, tanpa menyebut path lokal siapa pun
- [x] Update tabel ringkasan progres di roadmap ini jadi ✅ untuk Fase 1

---

## Fase 2 — Layout & Komponen Dasar (Desain) ✅

> Tujuan akhir fase: kerangka visual sesuai identitas Telkom University (lihat PRD §7) siap dipakai semua halaman.

### 2.1 Tema warna Tailwind ✅
- [x] Tambahkan custom color di `resources/css/app.css` lewat blok `@theme` (bukan `tailwind.config.js` — Tailwind v4 di Laravel 13 pakai config berbasis CSS, lihat catatan Fase 1.2): `--color-telu-red` (`#9F1521`), varian `telu-red-deep`/`telu-red-bright`/`telu-red-dark` (`#AC000F`/`#C51626`/`#8F131E`), `--color-telu-navy` (`#002147`), neutral (`telu-ink`/`telu-body`/`telu-muted`/`telu-border`/`telu-border-soft`/`telu-bg-soft`/`telu-bg-soft-2`), dan warna fungsional research group (`rg-citi`/`rg-dsis`/`rg-seal`/`rg-unknown`) dari prototype
- [x] Diverifikasi lewat browser preview: 17 token warna dicek satu-satu via `getComputedStyle`, semua cocok persis dengan hex yang didefinisikan di `docs/PRD.md` §7

### 2.2 Tipografi ✅
- [x] Pasang font **Inter** (400/500/600/700) via `laravel-vite-plugin/fonts` (`bunny()` helper di `vite.config.js`) — self-hosted lewat build Vite, bukan request eksternal ke Google/Bunny Fonts saat runtime. Dipilih dibanding Poppins karena prioritas dashboard ini adalah keterbacaan data padat (tabel/angka), bukan gaya marketing (lihat PRD §7 "prioritas keterbacaan data > dekorasi")
- [x] Set sebagai `--font-sans` di blok `@theme` (`resources/css/app.css`)
- [x] Diverifikasi lewat browser preview: `getComputedStyle(body).fontFamily` mengembalikan `Inter, ui-sans-serif, ...`, dan semua request font woff2/woff 200 OK dari `localhost:8000/build/assets/inter-*`

### 2.3 Master layout ✅
- [x] Buat `resources/views/layouts/app.blade.php`: top accent bar merah, navbar putih (brand + slot nav kosong, diisi Fase 2.6), area konten (`@yield('content')`), footer
- [x] Tombol hamburger mobile disiapkan strukturnya (belum fungsional — diaktifkan dengan Alpine.js di Fase 2.6 setelah ada isi navigasi)
- [x] Diverifikasi lewat browser preview pakai halaman uji sementara (`@extends('layouts.app')`, dihapus lagi setelah verifikasi): topbar/navbar/konten/footer tampil sesuai desain, `body` background `#F9F9F9` & font Inter benar, dan responsif — di lebar 375px nav desktop `display:none` & tombol hamburger `display:flex` (dicek lewat computed style, bukan cuma screenshot)

### 2.4 Komponen reusable ✅
- [x] Komponen KPI card (`x-kpi-card`) — card putih, aksen bar merah di atas, label + value + icon opsional bundar merah muda
- [x] Komponen page header dengan divider merah (`x-page-header`) — judul + subjudul opsional + bar merah tebal sesuai pola "section divider" di PRD §7
- [x] Komponen badge research group (`x-research-group-badge`) — CITI/DSIS/SEAL/Unknown, warna soft-background + ring sesuai token `rg-*` dari Fase 2.1
- [x] Diverifikasi lewat browser preview pakai halaman uji sementara (dihapus lagi setelah verifikasi): screenshot + `getComputedStyle` per badge, semua warna cocok persis dengan token; sempat ketemu & diperbaiki bug — icon KPI card kepakai `{{ }}` jadi ke-escape (tampil sebagai teks `&#128101;`), sudah diganti `{!! !!}` karena isinya markup/emoji terpercaya, bukan input user

### 2.5 Routing & halaman placeholder ✅
- [x] 6 controller (`DashboardController`, `TopicController`, `ExpertiseMapController`, `LecturerController`, `CollaborationController`, `RecommendationController`) + route bernama di `routes/web.php`: `dashboard` (`/`), `topics.index` (`/topik-dominan`), `expertise.index` (`/peta-keahlian`), `lecturers.index` (`/dosen`), `collaborations.index` (`/kolaborasi`), `recommendations.index` (`/rekomendasi`)
- [x] Halaman placeholder di `resources/views/pages/` untuk masing-masing (judul + subjudul via `x-page-header`, isi "sedang dalam pengembangan" via komponen baru `x-coming-soon` dengan referensi nomor fase yang akan mengisinya)
- [x] `welcome.blade.php` bawaan Laravel dihapus (tidak dipakai lagi — `/` sekarang langsung jadi Dashboard Utama), link brand di navbar diarahkan ke `route('dashboard')`
- [x] Diverifikasi: `php artisan route:list` menampilkan 6 route dengan benar; keenamnya dicek satu per satu lewat curl (HTTP 200 + judul `<h1>` sesuai), dan satu halaman dicek visual lewat screenshot preview

### 2.6 Navigasi ✅
- [x] Navbar desktop diisi 6 link (array `$navItems` di `layouts/app.blade.php`, di-loop dengan `@foreach`)
- [x] Active-state: link halaman aktif dapat `bg-telu-red/10 text-telu-red`, lainnya `text-telu-body`. Dicek pakai `request()->routeIs(...)` — Profil Dosen pakai wildcard `lecturers.*` (bukan exact match) karena Fase 4.4 akan menambah route detail dosen
- [x] Menu mobile pakai Alpine.js (`x-data="{ mobileOpen: false }"`, toggle hamburger ↔ ✕, panel `x-show`/`x-cloak`) — perlu tambah CSS rule `[x-cloak] { display: none !important; }` manual di `app.css` karena Tailwind v4 tidak menyediakannya bawaan
- [x] Diverifikasi lewat browser preview: warna active-state dicek presisi per link (`getComputedStyle`, hanya 1 link merah dalam satu waktu, berpindah benar antar halaman termasuk yang pakai wildcard), toggle mobile dicek buka→tutup dua kali serta active-state konsisten di panel mobile. Sempat ada false-negative saat verifikasi awal (tampak tidak jalan) — ternyata cache view Blade belum ke-clear setelah edit, hilang setelah `php artisan view:clear` + reload penuh

> **Catatan desain (setelah Fase 5.3 selesai):** navbar horizontal di atas (2.3–2.6) diganti jadi **sidebar kiri** atas permintaan langsung. Nav item, logic active-state (`request()->routeIs`), dan warna tetap sama — cuma orientasinya vertikal, dan versi mobile-nya jadi drawer geser dari kiri (bukan dropdown di bawah navbar). Detail perubahan & bug yang ketemu dicatat di bawah "Perubahan Desain: Sidebar" setelah Fase 5.

---

## Fase 3 — Data Layer Sementara (Migration + Seeder Dummy) ✅

> Tujuan akhir fase: tim bisa mengembangkan UI tanpa menunggu keputusan integrasi scraper (lihat PRD §4 & Fase 7). Skema dibuat **identik** dengan skema tabel relasional scraper supaya nanti tinggal disambungkan.
>
> **Catatan penyesuaian (arsitektur final):** target database final adalah **MySQL tunggal** (lihat `docs/PRD.md` §4). Struktur tabel relasional di fase ini tetap berlaku di MySQL — kolom JSON tinggal memakai tipe `JSON` MySQL, cast `array` Eloquent tetap sama. Dua hal dari skema prototipe lama yang **tidak lagi relevan**: (1) tabel `embeddings`/kolom vector `pgvector` sudah tidak dipakai (rekomendasi dihitung in-memory di sisi scraper, hanya hasil akhir disimpan); (2) kolom `sinta_metrics` tetap ada sebagai nama historis, tapi isinya kini murni metrik sitasi dari **Scopus** (bukan SINTA).

### 3.1 Migration tabel inti ✅
- [x] `lecturers` — 27 kolom persis sesuai `schema.sql` (identitas, metrik SINTA per platform, `ai_categories`/`sinta_metrics` sebagai kolom JSON dengan default `[]`/`{}`), unique constraint di `code`. Tidak pakai `timestamps()` Laravel karena tabel asal tidak punya kolom itu
- [x] `profiles` — FK `lecturer_id` → `lecturers.id` (nullable, cascade on delete), index bernama `idx_profiles_lecturer_id` mengikuti `database_details.md`
- [x] `publications` — FK sama polanya, index `idx_publications_lecturer_id`
- [x] Diverifikasi: `migrate:fresh` sukses; kolom & default value dicek lewat `Schema::getColumnListing()`/`PRAGMA table_info` (cocok 1:1 dengan `schema.sql`); index unique & FK dicek lewat `Schema::getIndexes()`/`PRAGMA foreign_key_list`; tes fungsional insert dosen+profil+publikasi lalu hapus dosen — cascade delete otomatis menghapus baris terkait (dicek row count sebelum/sesudah), lalu DB direset bersih lagi

### 3.2 Migration tabel relasi ✅
- [x] `keywords`, `research_interests`, `coauthors` — masing-masing FK tunggal ke `lecturers` (cascade on delete) + index bernama sesuai `database_details.md`
- [x] `recommendations` — dua FK ke `lecturers` (`lecturer_id`, `recommended_lecturer_id`), keduanya di-set eksplisit `constrained('lecturers')` karena nama kolom kedua tidak cocok dengan tebakan konvensi otomatis Laravel; kolom `score` (float) dan `reasons` (json)
- [x] `collaborations` — FK `lecturer_id_1`/`lecturer_id_2` (sama-sama eksplisit ke `lecturers`), `collaboration_count` default `1`, `shared_publications` (json). Urutan `id_1 < id_2` dijamin di sisi Python (`save_to_db.py`), bukan constraint DB — dicatat sebagai komentar di migration
- [x] `embeddings` di-skip sesuai rencana (kolom `vector(384)` tidak dipakai dashboard)
- [x] Diverifikasi: `migrate:fresh` sukses untuk 5 tabel baru; kolom/index/FK dicek lewat `Schema::getColumnListing()`/`getIndexes()`/`PRAGMA foreign_key_list` — cocok 100% dengan `schema.sql` & `database_details.md`; tes fungsional insert 2 dosen + 1 baris di tiap tabel relasi lalu hapus kedua dosen — cascade delete bersih di semua 5 tabel sekaligus (termasuk yang punya 2 FK), lalu DB direset

### 3.3 Model Eloquent ✅
- [x] 8 model dibuat (`Lecturer`, `Profile`, `Publication`, `Keyword`, `ResearchInterest`, `Coauthor`, `Recommendation`, `Collaboration`) — semua nama tabel cocok konvensi otomatis Eloquent, tidak perlu `$table` manual; `$timestamps = false` di semua model karena tabel asal tidak punya kolom itu
- [x] Relasi `hasMany`/`belongsTo` standar untuk `Profile`/`Publication`/`Keyword`/`ResearchInterest`/`Coauthor`
- [x] `Recommendation` & `Collaboration` sama-sama punya 2 FK ke `lecturers` → dibuat 2 relasi `belongsTo` terpisah tiap model (`lecturer()`/`recommendedLecturer()`, `lecturerOne()`/`lecturerTwo()`), plus di `Lecturer` masing-masing 2 `hasMany` (`recommendationsGiven`/`recommendationsReceived`, `collaborationsAsFirst`/`collaborationsAsSecond`)
- [x] `Lecturer::collaborations()` — method helper (bukan relasi Eloquent biasa) yang menggabungkan `collaborationsAsFirst` + `collaborationsAsSecond`, karena tabel `collaborations` cuma satu baris per pasangan (`id_1 < id_2`) sehingga kolaborator seorang dosen bisa ada di sisi manapun
- [x] Cast `array` untuk semua kolom JSON (`ai_categories`, `sinta_metrics`, `reasons`, `shared_publications`), cast `integer`/`float` untuk kolom numerik
- [x] Diverifikasi lewat tes fungsional end-to-end: insert 2 dosen + 1 baris di tiap tabel relasi pakai Eloquent (`Model::create()`), cek semua relasi jalan dua arah (termasuk `recommendationsGiven` vs `recommendationsReceived`, `lecturerOne`/`lecturerTwo`), cek cast JSON balik jadi array PHP, cek `collaborations()` helper mengembalikan hasil benar dari kedua sisi FK, lalu cascade delete dicek bersih di ketujuh tabel sekaligus. DB direset lagi setelahnya
- [x] PRD §6 diperbarui — diagram relasi awal ("belongsToMany self-referencing") dikoreksi jadi pola 2× `hasMany`/`belongsTo` + helper merge yang benar-benar diimplementasikan

### 3.4 Seeder data contoh ✅
- [x] `LecturerSampleSeeder` (dipanggil dari `DatabaseSeeder`) — 9 dosen sample diambil **apa adanya** dari `Keilmuan Dosen FIF.xlsx` (nama, NIP/code, prodi, kelompok keahlian dinormalisasi ke DSIS/SEAL/CITI, JFA, bidang keilmuan)
- [x] **Keputusan desain penting**: identitas & klasifikasi keahlian pakai data asli spreadsheet, tapi metrik riset/publikasi/coauthor/rekomendasi sengaja pakai **Faker** (teks lorem-ipsum acak, email `@example.org`, URL profil `example.org/...`) — bukan angka "realistis" karangan sendiri, supaya tidak seolah mengklaim capaian riset sungguhan atas nama dosen yang bersangkutan sebelum data asli tersambung di Fase 7
- [x] Data yang di-generate: 9 lecturers, 18 profiles, ~24 publications, 9 keywords, 9 research_interests, beberapa coauthors, 4 recommendations, 4 collaborations (dengan `lecturer_id_1 < lecturer_id_2` terjaga otomatis lewat helper urutan di seeder)
- [x] Diverifikasi: `migrate:fresh --seed` sukses; dicek row count tiap tabel, `research_group` dipastikan cuma berisi `DSIS`/`SEAL`/`CITI` (cocok dengan badge component Fase 2.4), spot-check satu dosen (relasi profiles/publications/keywords jalan, `ai_categories` ke-derive benar dari field), urutan `lecturer_id_1 < lecturer_id_2` dicek di semua baris collaborations, dan email dipastikan unik antar 9 dosen. Database sengaja **dibiarkan terisi** (tidak direset kosong) karena isinya jadi bahan kerja Fase 4

### 3.5 Verifikasi ✅
- [x] Query dasar dicek lewat route sementara (`/__data-check`, dihapus lagi setelah verifikasi) yang benar-benar jalan lewat HTTP request penuh — bukan cuma Tinker — supaya alur route → controller → Eloquent → DB → response terbukti utuh sebelum Fase 4 mulai membangun halaman sungguhan
- [x] Hasil dicek lewat browser preview (`preview_network`, isi response JSON dibaca langsung): `total_lecturers=9`, `total_publications=24`, `total_collaborations=4`, `total_recommendations=4`, breakdown `research_group` (CITI=2, DSIS=6, SEAL=1, totalnya pas 9), dan query relasi dosen contoh (publications/keywords) — semua angka konsisten dengan hasil verifikasi Tinker di Fase 3.4

---

## Fase 4 — Halaman Inti ✅

### 4.1 Dashboard Utama ✅
- [x] `DashboardController@index` query 4 angka ringkasan: total dosen (`Lecturer::count()`), total publikasi (`Publication::count()`), total kolaborasi (`Collaboration::count()`), dan jumlah bidang AI unik (flatten seluruh `ai_categories` semua dosen, dedup)
- [x] Halaman `pages/dashboard.blade.php` diisi 4× `x-kpi-card` (grid 1/2/4 kolom responsif) — tidak lagi pakai `x-coming-soon`
- [x] Diverifikasi lewat browser preview: nilai di kartu (`9`, `27`, `4`, `4` saat dicek) di-cross-check langsung ke query database via Tinker — cocok persis, termasuk daftar 4 kategori AI unik-nya. Total publikasi wajar berbeda tiap `migrate:fresh --seed` karena seeder Fase 3.4 pakai rentang acak Faker (2–4 publikasi/dosen), bukan bug
- [x] **Bug regresi ketemu saat audit ulang Fase 4** (`tests/Feature/ExampleTest.php`): begitu `/` mulai query DB sungguhan, test `RefreshDatabase` yang tadinya di-comment (bawaan skeleton Laravel) bikin test gagal 500 "no such table: lecturers" karena DB testing (`:memory:` di `phpunit.xml`) belum ke-migrate. Diperbaiki dengan mengaktifkan trait `RefreshDatabase` di test tsb — `php artisan test` lulus lagi 2/2

### 4.2 Peta Keahlian Dosen ✅
- [x] Komponen baru `x-lecturer-card` (dipakai bareng di 4.2 & 4.3) — nama, bidang keahlian, badge research group, prodi
- [x] `ExpertiseMapController@index` mengelompokkan dosen per `research_group`, urutan tetap CITI→DSIS→SEAL→lainnya
- [x] **Bug ditemukan & diperbaiki**: `Eloquent\Collection::except()` dipanggil di atas hasil `groupBy()` — error `Method Collection::getKey does not exist`, karena `groupBy()` pada Eloquent Collection tetap mengembalikan collection ber-tipe Eloquent walau isinya sub-collection (bukan Model). Diperbaiki dengan susun urutan key manual (`keys()->diff()->concat()`) tanpa `except()`
- [x] Diverifikasi lewat browser preview (network request + `preview_eval`, screenshot tool sempat macet lagi — bukan bug aplikasi): 9 kartu tampil, grouping CITI=2/DSIS=6/SEAL=1 persis benar, tiap kartu link ke `/dosen/{NIP}`, tidak ada error baru di `laravel.log` maupun console

### 4.3 Profil Dosen — list ✅
- [x] `LecturerController@index` dengan pencarian (`name`/`field`/`study_program`, query-string `search`) dan sort (`name`/`research_group`/`study_program`, query-string `sort`, whitelist kolom biar aman dari SQL injection lewat nama kolom)
- [x] Form pencarian + dropdown sort (auto-submit `onchange`) + tombol reset saat ada pencarian aktif
- [x] Empty-state kalau pencarian tidak ada hasil
- [x] Diverifikasi: default 9 dosen tampil; cari "Artificial" → tepat 2 dosen yang field-nya "Artificial Intelligence"; cari string tidak match → empty-state muncul; sort by `research_group` → urutan CITI→DSIS→SEAL sesuai alfabet kolom

### 4.4 Profil Dosen — detail ✅
- [x] `Lecturer::getRouteKeyName()` di-override jadi `code` (NIP, unique) — bukan `lecturer_code` yang di skema Python **tidak** ada unique constraint-nya, jadi tidak aman dipakai jadi URL key
- [x] Route `dosen/{lecturer}` (`lecturers.show`) + `LecturerController@show` — eager load `publications` (urut tahun terbaru), `keywords`, `researchInterests`, `profiles`
- [x] Halaman detail: header (nama, badge, prodi, JFA, kode+NIP), 3 KPI (sitasi/h-index/i10-index), tautan profil (SINTA/Google Scholar/Scopus/ORCID — yang belum ditautkan ditampilkan redup "belum ditautkan"), minat riset + keyword chip, daftar publikasi
- [x] `x-lecturer-card` (dipakai di 4.2 & 4.3) di-update jadi `<a href="{{ route('lecturers.show', $lecturer) }}">` — konsisten dengan pola "wire link begitu route-nya ada" yang dipakai juga di Fase 2.5→2.6
- [x] Diverifikasi lewat browser preview: buka detail Ade Romadhony — nama/badge/KPI/link platform/jumlah publikasi semua cocok dengan data di DB; regresi cepat ke 4 halaman lain (`/`, topik-dominan, kolaborasi, rekomendasi) dipastikan masih 200 OK, tidak kena dampak perubahan route key

---

## Fase 5 — Visualisasi Lanjutan ✅

### 5.1 Topik Dominan ✅
- [x] **Chart.js** dipasang (`npm install chart.js`, `chart.js/auto` diimpor & diekspos `window.Chart` di `resources/js/app.js`, sama pola dengan Alpine di Fase 1.3)
- [x] Tambah `@stack('scripts')` di `layouts/app.blade.php` (sebelum `</body>`) supaya halaman tertentu bisa push script sendiri — dipakai di sini, dan disiapkan juga untuk vis-network.js di Fase 5.2
- [x] `TopicController@index` menghitung jumlah dosen per kategori AI (flatten `ai_categories` semua dosen, `countBy()`, urut menurun)
- [x] Halaman `topics.blade.php`: donut chart (warna brand TelU + palet research group) berdampingan dengan tabel rincian angka pasti — sesuai prinsip PRD §7 "keterbacaan data > dekorasi", chart tidak berdiri sendiri tanpa angka
- [x] Diverifikasi lewat browser preview: chart instance dicek langsung (`Chart.getChart(canvas)`) — tipe `doughnut`, label & data cocok persis dengan tabel di sampingnya (Artificial Intelligence=2, sisanya=1 masing-masing), total 4 kategori sesuai hasil Fase 4.1. Sempat ada eval yang nyasar ke halaman lain di tengah pengecekan (glitch tool preview yang beberapa kali muncul di sesi ini, bukan bug aplikasi) — diverifikasi ulang dan konsisten. `php artisan test` tetap lulus 2/2, regresi ke 5 halaman lain aman

### 5.2 Kolaborasi ✅
- [x] **vis-network** dipasang (`npm install vis-network vis-data`, import dari `vis-network/standalone/esm/vis-network.js` supaya tidak perlu urus peer dependency manual, diekspos `window.VisNetwork`; CSS-nya di-`@import` di `app.css`)
- [x] `CollaborationController@index` mengubah data `collaborations` jadi node (dosen unik + `group`=research_group + `value`=jumlah koneksi) dan edge (pasangan + `value`=jumlah publikasi bersama), urut menurun
- [x] Halaman `collaborations.blade.php`: graph interaktif + legend warna (pakai ulang `x-research-group-badge`) + tabel "Daftar Kolaborasi" yang bisa diakses (nama dosen, jumlah publikasi bersama, `<details>` buat lihat judul publikasi bersama) — graph tidak berdiri sendiri tanpa data pasti, konsisten dengan pola Fase 5.1
- [x] **Bug ditemukan & diperbaiki**: warna node semua tampil biru (default vis-network), padahal seharusnya campuran hijau(DSIS)/biru(SEAL). Penyebab: config `groups` ditulis `{ DSIS: { background: '#...', border: '#...' } }` — vis-network butuh warna dibungkus di dalam `color: { background, border }`, bukan langsung di root object grup. Diperbaiki, diverifikasi ulang lewat screenshot: warna node sudah benar sesuai `research_group` tiap dosen
- [x] Diverifikasi lewat browser preview: data node/edge yang dikirim ke vis-network di-parse langsung dari HTML (bukan cuma dipercaya) — 7 node, 4 edge, semua `value`/`group`/pasangan cocok 100% dengan isi tabel `collaborations` (termasuk `value` degree Achmad Lukman=2 karena muncul di 2 kolaborasi, tercermin sebagai node terbesar di graph); tabel & legend juga dicek. `php artisan test` lulus 2/2, tidak ada regresi ke halaman lain

### 5.3 Rekomendasi Kolaborasi ✅
- [x] `RecommendationController@index` ambil semua `recommendations` (eager load `lecturer` + `recommendedLecturer`), urut menurun berdasarkan `score`, dipecah jadi top-3 dan sisanya — pola sama seperti prototype Streamlit ("Top Matches" + tabel ranks berikutnya)
- [x] Halaman `recommendations.blade.php`: 3 kartu "Top Matches" (skor, pasangan dosen, badge research group dosen yang direkomendasikan, daftar alasan) + tabel untuk sisa rekomendasi
- [x] Skor ditampilkan apa adanya (2 desimal, bukan diubah ke persentase) — konsisten dengan definisi `score FLOAT` di skema asli, tidak menambah asumsi skala yang tidak didokumentasikan
- [x] Diverifikasi lewat browser preview: 3 kartu top + 1 baris tabel = 4 rekomendasi total (cocok jumlah seed data), urutan skor menurun benar, satu baris tabel dicek detail (nama, pasangan, skor, alasan cocok persis dengan data ZHH→ACK di seeder). `php artisan test` lulus 2/2, tidak ada regresi

---

## Perubahan Desain: Navbar → Sidebar ✅

> Permintaan langsung dari user setelah Fase 5 selesai: menu navigasi (Dashboard, Topik Dominan, dst.) dipindah dari navbar horizontal ke sidebar kiri. Bukan bagian dari nomor fase manapun, dicatat terpisah di sini.

- [x] `layouts/app.blade.php` dirombak: `<header>` horizontal dihapus total, diganti `<aside>` (sidebar) — brand + nav vertikal ada di dalamnya. Karena sidebar dipakai untuk mobile *maupun* desktop sekaligus, daftar nav yang tadinya di-render dua kali (versi desktop dan versi mobile terpisah di Fase 2.6) sekarang cukup satu kali render saja — sedikit simplifikasi
- [x] Tombol hamburger dipindah ke top accent bar (dibuat `sticky md:static` supaya tetap terjangkau saat scroll di mobile, tapi tidak mengganggu sidebar yang juga sticky di desktop)
- [x] Sidebar: `fixed` + geser lewat CSS `translate` di mobile (drawer overlay + backdrop gelap, klik backdrop atau tombol ✕ menutup), berubah jadi `sticky` (ikut alur layout, mendorong konten ke kanan) mulai breakpoint `md`
- [x] Ditambah `@resize.window` listener di `<body>` yang reset `sidebarOpen = false` begitu lebar viewport ≥ 768px — jaga-jaga kalau user resize browser saat drawer mobile sedang terbuka
- [x] **Bug ditemukan & diperbaiki**: percobaan pertama pakai `:class="{ '!translate-x-0': sidebarOpen }"` buat force-override `-translate-x-full` saat drawer dibuka — ternyata perubahan class-nya sendiri sudah benar (dicek lewat `element.className`), tapi pengecekan `getBoundingClientRect()` yang dilakukan terlalu cepat (150–300ms) sempat kena tengah-tengah transisi CSS 200ms, jadi kelihatan seperti tidak jalan padahal sebenarnya jalan. Setelah tunggu transisi selesai, posisi sidebar benar. Bukan bug aplikasi, tapi jadi pengingat: transisi CSS butuh waktu tunggu lebih saat verifikasi otomatis
- [x] Ketemu juga miskonsepsi kecil saat verifikasi: cek awal pakai `getComputedStyle(aside).transform` yang selalu `none` — ternyata Tailwind v4 pakai properti CSS `translate` terpisah (bukan digabung ke `transform` seperti v3), jadi harus cek `getComputedStyle(aside).translate` untuk lihat state geser yang sebenarnya
- [x] Diverifikasi menyeluruh lewat browser preview: desktop — sidebar tampil statis di kiri, active-state benar & berpindah antar halaman; mobile (375px) — sidebar tersembunyi (`translate: -100%`, `left: -256px`), hamburger toggle buka (drawer geser masuk + backdrop muncul) dan tutup (tombol ✕ maupun klik backdrop, dua-duanya dicek terpisah), auto-reset saat resize balik ke desktop dicek juga. Regresi ke 7 route (termasuk halaman detail dosen) dan `php artisan test` (2/2) aman
- [x] PRD §7 (referensi desain) diperbarui — pola navigasi diganti dari "navbar horizontal" jadi "sidebar kiri", warna/logic active-state tidak berubah

---

## Fase 6 — Filter Global & Export ✅

> Catatan konteks: sebelum fase ini dikerjakan, user merombak desain visual banyak halaman (dashboard, peta keahlian, topik dominan, kolaborasi, rekomendasi, plus komponen `kpi-card`/`research-group-badge`/`lecturer-card`/layout sidebar) jadi gaya "premium" — gradient, glow, avatar inisial, ikon SVG, `card-premium`. Fase 6 mengikuti bahasa desain baru ini, bukan gaya polos Fase 2-5.

### 6.1 Filter global ✅
- [x] **Keputusan cakupan**: filter dipusatkan di halaman **Profil Dosen** sebagai satu filter bar komprehensif — bukan dipecah tipis-tipis di semua halaman. Alasan: "Filter Global" lebih masuk akal sebagai satu hub filter utama untuk halaman jelajah data (Profil Dosen), sementara Peta Keahlian sudah punya filter kelompok keahlian sendiri (tab Alpine.js, dibuat user) dan halaman lain (Dashboard, Topik Dominan, Kolaborasi, Rekomendasi) sifatnya ringkasan/agregat yang tidak butuh filter granular tambahan
- [x] `LecturerController@index` ditambah 4 filter query-string (`prodi`, `kelompok`, `bidang`, `tahun`) di atas `search`/`sort` yang sudah ada Fase 4.3 — opsi dropdown di-generate dinamis dari `distinct()` kolom terkait (tahun publikasi lewat `whereHas('publications', ...)`)
- [x] `lecturers.blade.php` ditulis ulang total mengikuti bahasa desain baru: filter bar `card-premium` dengan 5 kontrol (cari + 4 dropdown) + baris kedua (sort + reset + tombol terapkan bergaya gradient/scale-hover), empty-state premium (ikon SVG + pesan), grid `x-lecturer-card` (komponen ini sudah premium sejak dirombak user)
- [x] Diverifikasi lewat browser preview (semua fetch langsung ke endpoint, bukan asumsi): filter `prodi=S1 Informatika` → tepat 2 dosen yang benar; `kelompok=SEAL` → 1 dosen; `bidang=Artificial Intelligence` → 2 dosen; `tahun` (opsi dropdown 2019–2025 sesuai rentang Faker seeder) → filter `whereHas` bekerja; kombinasi `kelompok=DSIS&sort=study_program` → 6 dosen (cocok); empty-state muncul untuk pencarian tanpa hasil; link "Reset Filter" cuma muncul saat ada filter aktif. Regresi ke 5 halaman lain + `php artisan test` (2/2) aman

### 6.2 Export Excel ✅
- [x] `composer require maatwebsite/excel` (v3.1.69, kompatibel Laravel 13)
- [x] `LecturersExport` — nerima `Collection` dosen yang **sudah difilter** dari controller (bukan query ulang di dalam Export class), supaya file yang di-download konsisten dengan filter aktif di halaman. `CollaborationsExport` — daftar kolaborasi lengkap
- [x] `DashboardSummaryExport` (`WithMultipleSheets`) — 4 sheet terpisah (`RingkasanSheet`, `KelompokKeahlianSheet`, `PublikasiTerbaruSheet`, `KolaborasiTeraktifSheet` di `app/Exports/Sheets/`) supaya satu file Excel mencerminkan seluruh isi Dashboard Utama, bukan cuma satu angka
- [x] `LecturerController`/`DashboardController` di-refactor: logic query filter/data dipindah ke method private (`filteredLecturers()`, `dashboardData()`) supaya dipakai bareng oleh halaman (`index`) dan export (`export`) — tidak duplikasi kode
- [x] Komponen baru `x-export-buttons` (terima `excel-route`, `pdf-route` opsional buat Fase 6.3, `query` buat neruskan filter aktif) dipasang di 3 halaman: Dashboard, Profil Dosen (bawa query filter aktif), Kolaborasi
- [x] Route export statis (`/dosen/export/excel`, dst.) sengaja didaftarkan **sebelum** `/dosen/{lecturer}` supaya kata "export" tidak ketangkep sebagai parameter kode NIP
- [x] Diverifikasi lewat browser preview: keempat endpoint export dicek `fetch()` — status 200, `Content-Type` xlsx yang benar, magic bytes ZIP (`50 4b 03 04`, membuktikan file benar-benar xlsx valid bukan cuma nama file), dan file dengan filter (`kelompok=DSIS`) ukurannya lebih kecil dari tanpa filter. Lebih presisi lagi: isi file dibaca langsung pakai PhpSpreadsheet lewat Tinker — 6 baris export dosen semuanya DSIS (cocok), 4 baris export kolaborasi (cocok jumlah data). `php artisan test` lulus 2/2

### 6.3 Export PDF ✅
- [x] `composer require barryvdh/laravel-dompdf` (v3.1.2)
- [x] `layouts/pdf.blade.php` — layout cetak terpisah dari `layouts/app.blade.php` (dompdf tidak proses Tailwind hasil build Vite; CSS ditulis inline sederhana, warna brand TelU tetap dipakai untuk header/section title)
- [x] 3 view PDF (`pdf/dashboard.blade.php`, `pdf/lecturers.blade.php`, `pdf/collaborations.blade.php`) — konten & kolom disamakan dengan versi Excel-nya masing-masing supaya kedua format konsisten
- [x] Method `exportPdf()` ditambah di 3 controller, pakai ulang method private yang sama (`filteredLecturers()`, `dashboardData()`) dengan `export()` (Excel) — satu sumber data untuk kedua format
- [x] `x-export-buttons` diisi `pdf-route` di 3 halaman (prop ini sudah disiapkan sejak 6.2 supaya tidak perlu ubah komponen lagi)
- [x] Diverifikasi lewat browser preview: keempat endpoint PDF — status 200, `Content-Type: application/pdf`, magic bytes `%PDF-`, dan file dengan filter (`kelompok=SEAL`) jauh lebih kecil dari tanpa filter. Karena dompdf men-compress content stream (jadi teks tidak bisa di-*grep* langsung dari file jadi), verifikasi isi dilakukan dengan cara lebih tepat: render langsung Blade view `pdf.lecturers`/`pdf.dashboard` lewat Tinker dengan data yang sama persis seperti yang dipakai controller — HTML sumbernya mengandung nama dosen & label kelompok keahlian yang benar sebelum dikonversi ke PDF. `php artisan test` lulus 2/2, tidak ada regresi ke halaman lain

### Audit ulang Fase 6 (setelah 6.1–6.3 selesai)
- [x] Migrate+seed dari nol, `route:list`, `php artisan test` — semua sehat
- [x] **Bug ditemukan — di skrip verifikasi sendiri, bukan di aplikasi**: saat cek ulang jumlah kartu dosen per filter pakai regex `/\/dosen\/[^"]+"/g`, hasilnya selalu 2 lebih besar dari jumlah sebenarnya di database. Penyebab: sejak 6.2 menambahkan tombol export (`href="/dosen/export/excel"` dan `/dosen/export/pdf`) di halaman yang sama, kedua link itu ikut cocok pola regex yang sama dengan link kartu dosen. Diperbaiki dengan menambah filter `.filter(h => !h.includes('/export/'))` pada skrip pengecekan — setelah itu semua angka cocok 100% dengan query langsung ke database (CITI=2, DSIS=6, SEAL=1, S1 Informatika=2, Artificial Intelligence=2, publikasi tahun 2025=5)
- [x] Isi file export (bukan cuma ukuran) diverifikasi ulang presisi untuk kombinasi filter `kelompok=CITI`: Excel (PhpSpreadsheet) dan sumber HTML PDF sama-sama menghasilkan tepat 2 baris (Abdullah Hanifan, Aji Gautama Putrada) — cocok dengan halaman web
- [x] Dikonfirmasi juga: link tombol Excel/PDF di halaman Profil Dosen otomatis ikut membawa query string filter yang sedang aktif (`?kelompok=DSIS&prodi=...`), bukan selalu mengarah ke data tanpa filter
- [x] `laravel.log` bersih total setelah seluruh audit, tidak ada error baru

---

## Fase 7 — Integrasi Data Real (Scraper) ⛔ Menunggu Prasyarat

> Arsitektur sudah ditetapkan (lihat `docs/PRD.md` §4): **satu database MySQL** dipakai bersama scraper & dashboard. Scraper menulis langsung ke MySQL, Laravel membaca dari DB yang sama. Fase ini menunggu prasyarat infra (instance MySQL bersama tersedia) dan penyesuaian di repo scraper.

### 7.1 Prasyarat infra & repo scraper
- [ ] Instance **MySQL bersama** disiapkan (host disepakati tim — lihat Open Question PRD §11), kredensial dibagikan via `.env` masing-masing
- [ ] Repo scraper (`Telkom-University-Lecturer-Scraper-and-Dashboard-Prototype`) disesuaikan: driver DB ke MySQL, dan logic penulisan menghormati penanda `is_overridden` per field (§4.3 PRD) — pekerjaan di repo lain, di luar `keluarga-kp`

### 7.2 Implementasi koneksi
- [ ] Arahkan `.env` Laravel ke instance MySQL bersama, matikan seeder dummy dari Fase 3
- [ ] Skema MySQL final selaras dengan migration Fase 3 (tanpa `embeddings`/pgvector, `sinta_metrics` diisi metrik Scopus — lihat catatan penyesuaian Fase 3)
- [x] Referensi historis: command `php artisan import:lecturers {path}` sudah pernah dibuat untuk trial import identitas dosen dari spreadsheet — lihat "Trial Import" di bawah

### 7.3 Mekanisme koreksi Admin (§4.3 PRD)
- [ ] Tentukan struktur penyimpanan flag `is_overridden` (kolom per-field vs tabel `lecturer_field_overrides`) — lihat Open Question PRD §11
- [ ] Pastikan penulisan scraper melewati field yang ditandai override, sehingga koreksi Admin tidak tertimpa

### 7.4 Validasi
- [ ] Spot-check beberapa dosen: data di dashboard cocok dengan data asli hasil scraping Scopus
- [ ] Uji skenario override: Admin koreksi 1 field → jalankan scraper ulang → field itu tidak berubah

### 7.5 Update dokumentasi
- [ ] Update PRD & roadmap ini begitu integrasi berjalan penuh

---

## Trial Import: Data Identitas Dosen dari Spreadsheet (belum final)

> User minta lanjut Fase 7 tanpa integrasi scraper penuh dulu: **"untuk sekarang belum ada keputusan namun sementara import data yang sekarang dahulu untuk cek hasilnya seperti apa"**. Ini BUKAN integrasi final (yang akan menulis langsung ke MySQL dari scraper, lihat PRD §4) — cuma eksperimen sekali jalan pakai data yang sudah ada, supaya tim bisa lihat gambaran dashboard dengan roster dosen yang lebih lengkap.

- [x] **Cek dulu data apa yang benar-benar tersedia**: repo scraper (`Telkom-University-Lecturer-Scraper-and-Dashboard-Prototype`) ternyata **tidak punya** `.env`, dump DB, atau file JSON/CSV hasil scraping tersimpan — folder `data/raw`/`data/json`/`data/cleaned` cuma placeholder kosong. Satu-satunya data nyata yang ada di repo itu adalah `data/input/Keilmuan Dosen FIF.xlsx` — spreadsheet yang sama yang sudah dipakai sebagian (9 dosen sample) di seeder Fase 3.4
- [x] Spreadsheet ternyata punya **183 dosen unik** di sheet "ALL" (lengkap dengan Prodi/Kelompok Keahlian/JFA/Keilmuan) — jauh lebih banyak dari 9 sample yang dipakai sebelumnya. Sheet1 terpisah (161 baris) punya NIP tapi cuma bisa di-join ke sheet ALL lewat nama (tidak ada kolom ID yang sama di kedua sheet)
- [x] Dibuat command baru `php artisan import:lecturers {path}` (`app/Console/Commands/ImportLecturersFromSpreadsheet.php`) — `{path}` WAJIB diisi tiap kali dijalankan (bukan default hardcode), supaya tidak bergantung pada struktur folder laptop siapa pun
- [x] Logic command: baca sheet ALL + Sheet1 pakai PhpSpreadsheet (sudah ada sebagai dependency `maatwebsite/excel`), dedup nama yang ke-input dobel, join NIP lewat nama (dinormalisasi uppercase+trim), fallback pakai KODE 3-huruf kalau NIP tidak ketemu, extract `research_group` dari teks "...(DSIS)" dst lewat regex, lalu `updateOrCreate` berdasarkan `code` — supaya dosen yang **sudah ada** (9 sample Fase 3.4) di-update identitasnya tanpa kehilangan relasi Faker (publikasi/kolaborasi/rekomendasi) yang sudah nempel, dan dosen **baru** dibuat bersih tanpa relasi tambahan
- [x] **Prinsip yang dipertahankan dari Fase 3.4**: identitas & klasifikasi (nama, NIP/kode, prodi, kelompok keahlian, JFA, keilmuan) diisi dari data ASLI spreadsheet — tapi metrik sitasi, publikasi, profil tautan, kolaborasi, dan rekomendasi **tidak dikarang** untuk 174 dosen baru, dibiarkan kosong/default karena data itu memang belum tersedia sampai integrasi scraper asli (7.2) jalan. Keyword & minat riset tetap diisi dari kolom Keilmuan asli (bukan fabrikasi) untuk dosen baru saja (dosen lama sudah punya keyword sendiri dari seeder, tidak ditimpa)
- [x] Daftar "bidang AI-related" (dipakai untuk `ai_categories` & KPI "Bidang AI") disusun dari 45 nilai kolom Keilmuan yang **benar-benar muncul** di spreadsheet (bukan tebakan) — 20 di antaranya masuk kategori AI (Artificial Intelligence, Machine Learning, NLP, Computer Vision, Data Mining, Recommender System, dst)
- [x] Diverifikasi lewat Tinker sebelum & sesudah run kedua (cek idempotency): 183 baris diproses → 174 dosen baru + 9 update (persis 9 sample lama, tidak ada duplikat); run kedua → 0 baru + 183 update, jumlah `keywords`/`publications`/`collaborations` **tidak bertambah** (bukti tidak ada duplikasi saat command dijalankan ulang); dosen lama (Ade Romadhony) dicek masih punya 4 publikasi + 2 profil (relasi Faker utuh, tidak tertimpa); dosen baru (Kemas Muslim Lhaksmana, field "Text Mining & Processing") dicek identitas + `ai_categories` + keyword benar, 0 publikasi (jujur, sesuai desain)
- [x] Diverifikasi lewat browser preview: Dashboard Utama (183 dosen, 28 publikasi — tidak berubah, 20 kategori AI unik, breakdown CITI=49/DSIS=83/SEAL=51), Peta Keahlian & Profil Dosen (183 kartu masing-masing), halaman detail dosen baru (`/dosen/13820075-1`) — semua 200 OK, tidak ada error, waktu load masih wajar (~0.5–1.2 detik, PHP dev server tanpa opcache). `php artisan test` tetap lulus 2/2
- [x] File sumber `Keilmuan Dosen FIF.xlsx` **sengaja tidak disalin ke dalam repo `keluarga-kp`** — berisi NIP (identifier individu) 183 dosen sungguhan, jadi tidak dikomit ke git untuk menjaga privasi. Command menerima path eksternal sebagai argumen, bukan file bawaan repo

**Yang masih kosong/belum ada** (jujur, bukan bug): publikasi/sitasi/profil tautan/kolaborasi/rekomendasi untuk 174 dosen baru — semua itu perlu data asli hasil scraping Scopus yang baru bisa didapat setelah integrasi Fase 7 dijalankan sungguhan (scraper menulis langsung ke MySQL), bukan cuma spreadsheet identitas.

---

## Revisi Desain: "De-AI-ify" (bertahap, mulai dari Dashboard)

> User minta desain direvisi supaya tidak terlihat seperti hasil generate AI generik: **"revisi desain supaya tidak mirip dengan hasil buatan ai, mulai dari dashboard"**. Dikerjakan bertahap per halaman, dimulai dari Dashboard Utama. Halaman lain menyusul di sesi berikutnya.

**Pola dekoratif yang dihapus/dihindari** (ciri khas "AI-generated UI" yang jadi target): gradient hero banner, blur orb dekoratif, badge/status dot dengan `animate-pulse`, lingkaran avatar berisi inisial dengan gradient, efek hover-lift (`translateY` + shadow membesar) & hover-scale pada kartu/tombol, glassmorphism/backdrop-blur pada nav, timeline dengan circle marker, kombinasi doughnut+progress-bar berlebihan.

### Dashboard Utama (`resources/views/pages/dashboard.blade.php`)
- [x] Hero banner gradient + blur orb dekoratif + badge "Portal Riset & Kolaborasi AI" berpulsa + tombol CTA hover-scale dihapus total, diganti `<x-page-header>` standar (konsisten dengan halaman lain) + baris tombol export terpisah
- [x] Kartu KPI (`x-kpi-card`) disederhanakan: accent bar gradient di atas kartu, invert warna ikon saat hover, dan scale-on-hover dihapus — sekarang ikon dalam kotak warna flat, kartu diam saat hover
- [x] Widget "Publikasi Terbaru" & "Kolaborasi Teraktif" diganti dari pola timeline/stacked-avatar jadi daftar baris polos (`divide-y`), lebih sesuai konten tabular yang sebenarnya
- [x] Kartu Kelompok Keahlian (CITI/DSIS/SEAL) diflatkan, cuma border kiri 4px berwarna sebagai aksen (bukan gradient/shadow)
- [x] `export-buttons` komponen: hover:scale dihapus, radius & padding dirapikan
- [x] `.card-premium` di `resources/css/app.css` diflatkan (translateY+shadow-on-hover dihapus, jadi kartu 1px border datar); `.bg-grid-pattern` & `.glass-nav` (dead code, tidak dipakai lagi) dihapus
- [x] `layouts/app.blade.php`: `bg-grid-pattern` di `<body>` & dot status `animate-pulse` di top bar dihapus
- [x] Diverifikasi lewat `preview_eval` (screenshot tool sempat flaky/timeout berkali-kali, jadi dicek langsung lewat DOM/computed style): tidak ada elemen gradient/blur/pulse/avatar-circle tersisa di `<main>`, kartu berbentuk flat border tanpa box-shadow, KPI menampilkan angka benar (183 dosen dst), kedua daftar widget menampilkan total 8 baris dengan benar
- [x] Regresi: `kpi-card` juga dipakai `lecturer-detail.blade.php` (Sitasi/H-Index/i10-Index) — dicek masih render benar (Ade Romadhony: Sitasi 163, H-Index 7, i10-Index 11) setelah komponen disederhanakan
- [x] `php artisan test` tetap lulus 2/2, `storage/logs/laravel.log` bersih, 6 route utama tetap 200 OK

### Halaman sisa (lanjutan revisi desain)
- [x] **Peta Keahlian** (`expertise.blade.php`) — tab bar `rounded-2xl` + `shadow` diganti tab garis-bawah (border-bottom) datar; banner kelompok pakai `card-premium` + border-kiri berwarna (bukan `shadow-sm`/`bg-white/80`); animasi `x-transition` translate dihapus; empty-state pakai `<x-empty-state>`
- [x] **Topik Dominan** (`topics.blade.php`) — kartu chart & tabel diflatkan (hapus `shadow-sm`, heading pakai gaya section standar); progress bar disederhanakan (hapus `shadow-sm` pada dot, border pada bar, `transition duration-500`)
- [x] **Profil Dosen (list)** (`lecturers.blade.php`) — form filter diflatkan (`rounded-xl`→`rounded-md`, hapus `shadow-sm`, label `font-bold`→`font-semibold`), tombol Terapkan datar; empty-state pakai `<x-empty-state>`
- [x] **Profil Dosen (detail)** (`lecturer-detail.blade.php`) — avatar `rounded-2xl shadow-sm`→`rounded-md` datar; kartu partner rekomendasi disederhanakan dari avatar-bulat+skor jadi baris flat (nama + skor)
- [x] **Kolaborasi** (`collaborations.blade.php`) — kartu graph & tabel diflatkan (hapus `shadow-sm`/`shadow-inner`, `rounded-2xl`→`rounded-md`); node network graph dimatikan shadow-nya; tabel pakai avatar-bulat dihapus jadi nama polos; tip navigasi disederhanakan jadi teks biasa
- [x] **Rekomendasi** (`recommendations.blade.php`) — kartu top match dirombak: hapus accent bar merah, connector box `shadow-inner`, avatar-bulat, panah-dalam-lingkaran, score badge — jadi kartu flat (skor + pasangan nama + alasan chip datar); tabel alternatif diflatkan
- [x] Komponen bersama: `lecturer-card` diflatkan (avatar `rounded-xl`→`rounded-md`, hapus border opacity-variant), dan dibuat komponen baru **`<x-empty-state>`** untuk menyeragamkan empty-state (sebelumnya tiap halaman punya blok `rounded-2xl ... shadow-sm` sendiri)
- [x] Diverifikasi: `npm run build` sukses setelah semua edit (semua class Tailwind baru valid & ter-generate), directive Blade seimbang (`@if/@endif`, `@foreach/@endforeach`, `@forelse/@endforelse`), tidak ada variabel yatim (`$init1`/`$init2` dsisa). Catatan: verifikasi visual via browser preview belum dilakukan di sesi ini karena PHP tidak terpasang di mesin — perlu dicek tampilannya saat server jalan

**Belum disentuh** (di luar cakupan, sengaja dipertahankan): sidebar/nav chrome di `layouts/app.blade.php` (ikon SVG, `glow-red` pada logo FIF).

---

## Fase 8 — Auth & Panel Admin

> Mengimplementasikan peran Admin (PRD §3.1) untuk mengoreksi data dosen yang dilaporkan salah. UI mengikuti desain dashboard yang sama (PRD §7), bukan template admin terpisah.

### 8.1 Autentikasi ✅
- [x] **Catatan penyesuaian**: paket **Laravel Breeze tidak dipasang** — PHP di lingkungan pengembangan sesi ini awalnya tidak terdeteksi di PATH (ternyata ada, terpasang via WinGet di `%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.3_...`, cuma belum ditambahkan ke PATH sistem). Karena kebutuhan login hanya untuk Admin (tanpa registrasi/verifikasi email/reset password), autentikasi ditulis manual pakai **Auth facade & middleware `auth`/`guest` bawaan Laravel** — tabel `users`/`sessions`/`password_reset_tokens` sudah ada dari migration default, jadi tidak perlu dependency tambahan. Hasil akhirnya setara Breeze untuk kebutuhan kita, dengan footprint lebih kecil
- [x] Dibuat: `app/Http/Requests/Auth/LoginRequest.php` (validasi + rate limit 5x percobaan per email+IP), `app/Http/Controllers/Auth/AuthenticatedSessionController.php` (`create`/`store`/`destroy`), `routes/auth.php` (didaftarkan dari `bootstrap/app.php` via closure `then`) — **tidak ada route registrasi publik sama sekali**, sesuai PRD §3.1
- [x] View: `resources/views/layouts/guest.blade.php` (layout minim terpusat, tanpa sidebar) + `resources/views/auth/login.blade.php` (form email/password/remember, gaya flat konsisten dengan dashboard — `card-premium`, `rounded-md`, palet TelU)
- [x] **Seeder akun Admin**: `database/seeders/AdminSeeder.php` — baca `ADMIN_EMAIL`/`ADMIN_PASSWORD` dari `.env` (fallback default kalau belum di-set), `updateOrCreate` berdasarkan email (idempotent, aman dijalankan ulang). Dipanggil dari `DatabaseSeeder` (menggantikan `User::factory()` "Test User" bawaan scaffold yang dihapus). `.env.example` & `.env` lokal ditambah placeholder `ADMIN_EMAIL`/`ADMIN_PASSWORD` dengan catatan wajib diganti sebelum deploy
- [x] Diverifikasi end-to-end lewat browser preview (server `php artisan serve` berhasil dijalankan setelah PHP ditambahkan ke PATH sesi ini): `migrate:fresh --seed` sukses, admin ter-seed (`admin@fif.telkomuniversity.ac.id`, password ter-hash benar via `Hash::check`), `GET /login` render form dengan benar, login sukses redirect ke Dashboard (fallback sampai panel admin Fase 8.3 ada), middleware `guest` bekerja (user yang sudah login diarahkan menjauh dari `/login`), route `login`/`logout` terdaftar di `route:list`. `php artisan test` tetap lulus, `storage/logs/laravel.log` tidak ada error baru dari pengujian ini (2 entri lama di log tersebut bertanggal 17 Juli, bukan dari sesi ini)
- [x] `.claude/launch.json` dibuat menunjuk ke path PHP WinGet ini, supaya preview server bisa dijalankan lagi di sesi berikutnya pakai `preview_start`

### 8.2 Proteksi route ✅
- [x] Dibuat `routes/admin.php` — grup route prefix `/admin`, nama `admin.*`, di balik middleware `auth`; didaftarkan dari `bootstrap/app.php` (pola sama dengan `routes/auth.php` di 8.1)
- [x] `app/Http/Controllers/Admin/DashboardController.php` + `resources/views/admin/dashboard.blade.php` — halaman placeholder Panel Admin (`extends layouts.app`, jadi otomatis pakai sidebar & gaya flat yang sama dengan dashboard publik), berisi info user login + tombol Keluar. Form koreksi data sungguhan menyusul di 8.3
- [x] Redirect login (`AuthenticatedSessionController@store`) diarahkan ke `route('admin.dashboard')` (pakai `redirect()->intended()`, jadi kalau user mencoba akses halaman admin tertentu sebelum login, setelah login diarahkan balik ke situ)
- [x] Top bar `layouts/app.blade.php` dibuat auth-aware: tampil link "Masuk Admin" kalau belum login, atau "Panel Admin" kalau sudah login (pakai `@auth`/`@else`/`@endauth`) — supaya `/login` & `/admin` bisa dijangkau dari UI, bukan cuma lewat URL manual
- [x] Diverifikasi lewat browser preview (server `php artisan serve`): akses `/admin` tanpa login → diarahkan ke `/login` (middleware `auth` bekerja); setelah login → langsung ke Panel Admin; dashboard publik (`/`) tetap bisa diakses tanpa sesi login; tombol "Keluar" di Panel Admin berhasil logout & kembali ke halaman login; link "Panel Admin"/"Masuk Admin" di top bar berubah sesuai status login. `npm run build` sukses, `php artisan test` tetap lulus 2/2

### 8.3 Form koreksi data dosen ✅
- [x] **Keputusan struktur override** (menuntaskan open question PRD §11): dipilih **tabel terpisah** `lecturer_field_overrides` (`lecturer_id`, `field`, unique gabungan) — bukan kolom boolean per-field di `lecturers`. Migration `2026_07_23_000001_create_lecturer_field_overrides_table.php` (FK cascade ke `lecturers`), model `LecturerFieldOverride`, plus helper `Lecturer::fieldOverrides()`/`hasOverriddenField()`
- [x] `app/Http/Controllers/Admin/LecturerController.php` — `index` (daftar dosen + search nama/kode/NIP, paginated), `edit` (form per dosen), `update` (validasi, bandingkan nilai lama vs baru per field, simpan, lalu `LecturerFieldOverride::updateOrCreate` untuk tiap field yang **benar-benar berubah**). Field yang bisa dikoreksi: nama, nama+gelar, kode dosen, prodi, kelompok keahlian, jabatan fungsional, bidang keilmuan, sitasi, h-index, i10-index (identitas+klasifikasi+metrik sesuai PRD §5 — publikasi/kolaborasi/rekomendasi individual di luar cakupan form ini)
- [x] Routes: `admin.lecturers.index`/`edit`/`update` di `routes/admin.php` (di dalam grup middleware `auth` yang sama dengan 8.2)
- [x] View: `admin/lecturers/index.blade.php` (tabel + search, gaya sama dengan halaman Profil Dosen publik) dan `admin/lecturers/edit.blade.php` (form 2 kolom, badge "Sudah dikoreksi — dilindungi dari import" muncul di field yang sudah pernah di-override) — keduanya `extends layouts.app`, otomatis pakai sidebar & gaya flat yang sama
- [x] Panel Admin (`admin/dashboard.blade.php`) ditambah tombol "Cari & Koreksi Data Dosen" yang mengarah ke daftar dosen
- [x] **Command import scraper (`ImportLecturersFromSpreadsheet`, dari Fase 7 trial) disesuaikan** untuk menghormati override: sebelum `updateOrCreate`, field yang punya baris di `lecturer_field_overrides` untuk dosen itu di-`unset` dari data yang mau ditulis — jadi command ini sekaligus jadi bukti nyata mekanisme §4.3 PRD, bukan cuma didesain di atas kertas. Ringkasan command ditambah baris "Field dilewati (sudah dikoreksi Admin)"
- [x] Diverifikasi lewat browser preview: login → Panel Admin → daftar dosen (9 dosen tampil, search "Achmad" hanya menampilkan 1 hasil yang cocok) → buka form koreksi salah satu dosen → ubah field "Bidang Keilmuan" → simpan → pesan sukses tampil ("Field yang diubah (field) ditandai override...") → badge "Sudah dikoreksi — dilindungi dari import" muncul di field itu setelah reload form
- [x] **Diverifikasi lewat Tinker (simulasi re-import)**: dijalankan urutan yang persis meniru logic command — field `field` yang sudah di-override TETAP nilai koreksi Admin meski "data scraper" baru dicoba ditulis ke situ, sedangkan field `academic_rank` yang belum di-override berhasil ter-update normal ke nilai baru. Ini membuktikan filtering override bekerja tepat: melindungi field yang dikoreksi, tidak memblokir field lain
- [x] `migrate:fresh --seed` sukses dengan tabel baru, `php artisan test` tetap lulus 2/2, `storage/logs/laravel.log` tidak ada error baru

### 8.4 Verifikasi ✅
- [x] Ditambah **PHPUnit Feature test** (bukan cuma verifikasi klik manual di 8.2/8.3, supaya jadi regresi otomatis): `tests/Feature/AdminAuthTest.php` (guest diblokir dari `/admin` & diarahkan ke `/login`; Admin bisa login & akses `/admin`; dashboard publik tetap 200 tanpa login; login gagal dengan password salah tetap sebagai guest) dan `tests/Feature/LecturerOverrideTest.php` (edit field via panel admin membuat baris override & mengubah nilai; **command `import:lecturers` dijalankan sungguhan** dengan file xlsx sementara yang dibuat via PhpSpreadsheet di dalam test — field yang di-override tetap nilai koreksi manual, field lain ikut ter-update dari "data scraper")
- [x] **Bug ditemukan & diperbaiki** lewat test ini (bukan cuma dari klik manual): form koreksi awalnya mengizinkan `citation_count`/`h_index`/`i10_index` dikosongkan → jadi `null` → melanggar constraint `NOT NULL DEFAULT 0` di migration `lecturers` (kolom-kolom itu memang tidak nullable, beda dari kolom string lain yang semuanya `nullable()`). Diperbaiki: `LecturerController@update` sekarang fallback nilai kosong ke `0` untuk ketiga kolom numerik itu sebelum `$lecturer->update()`
- [x] Semua 8 test (`php artisan test`) lulus setelah perbaikan; `storage/logs/laravel.log` dicek — satu entri error yang ada adalah residu dari run yang gagal SEBELUM fix (bukan error baru setelah fix, dikonfirmasi lewat re-run bersih)

---

## Fase 9 — Polish, QA & Serah Terima

### 9.1 Responsif
- [ ] Cek layar desktop 1280–1440px minimal, idealnya tablet

### 9.2 Lintas browser
- [ ] Cek Chrome, Edge, Firefox minimal

### 9.3 Review UI/UX
- [ ] Bandingkan dengan referensi desain Telkom University (PRD §7)

### 9.4 Dokumentasi akhir
- [ ] Sinkronkan README/PRD/roadmap dengan kondisi final proyek

### 9.5 Demo & serah terima
- [ ] Demo ke Satgas AI FIF
