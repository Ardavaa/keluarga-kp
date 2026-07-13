# PRD — Dashboard Peta Keahlian & Kolaborasi Riset Dosen FIF (Telkom University)

| | |
|---|---|
| **Repo** | `keluarga-kp` (dashboard, Laravel) |
| **Repo sumber data** | `Telkom-University-Lecturer-Scraper-and-Dashboard-Prototype` (scraper + prototype Streamlit) |
| **Tim** | Kelompok 1 KP — Syahdan Rizqi Ruhendy (103012330308), Muhammad Ghozy Abdurrahman (103012330264), Muhammad Karov Ardava Barus (103052300001) |
| **Status dokumen** | Draft v2 — arsitektur data (§4) sudah diputuskan: Opsi A, PostgreSQL (bukan Supabase) |
| **Catatan portabilitas** | Dokumen ini sengaja tidak menyebut path folder lokal siapa pun. Semua path di bawah bersifat relatif terhadap root masing-masing repo agar konsisten di laptop anggota tim manapun. |

---

## 1. Latar Belakang

Kelompok 1 mengerjakan KP dengan judul **"Dashboard Visualisasi Topik Riset dan Peta Keahlian Dosen untuk Mendukung Kolaborasi AI di FIF"**. Tujuannya membantu Satgas AI FIF memetakan kekuatan riset, topik dominan, klaster keahlian dosen, dan peluang kolaborasi antar dosen.

Sumber data sudah tersedia dari repo `Telkom-University-Lecturer-Scraper-and-Dashboard-Prototype`, yang berisi:
- Pipeline scraping (Playwright, OpenAlex API, SINTA) + parsing AI (Gemini) yang menghasilkan profil, publikasi, keyword, dan co-authorship dosen FIF.
- Database **PostgreSQL** (self-hosted/managed biasa, bukan Supabase — lihat §4) dengan ekstensi `pgvector`, berisi ±161 profil dosen, ±10.226 judul publikasi, ±1.030 pasangan co-authorship precomputed.
- Prototype dashboard **Streamlit** (`dashboard.py`) yang sudah membuktikan alur data: profil dosen, statistik riset FIF, dan jaringan kolaborasi.

Data mentah ini juga selaras dengan spreadsheet acuan `Keilmuan Dosen FIF.xlsx` yang berisi pemetaan resmi Prodi → Kelompok Keahlian (CITI / DSIS / SEAL) → Keilmuan per dosen.

**Tugas KP ini secara spesifik meminta output akhir berbentuk dashboard**, sehingga scraper Python dianggap sebagai *data pipeline* (system of record), dan **Laravel dashboard di repo `keluarga-kp` menjadi lapisan presentasi/produk akhir** yang dinilai.

---

## 2. Tujuan

1. Membangun dashboard web (Laravel) yang menyajikan ulang data dari database hasil scraping dengan UX yang lebih baik dan sesuai identitas visual Telkom University, dibanding prototype Streamlit saat ini.
2. Memenuhi seluruh fitur wajib yang diminta di dokumen tugas KP (lihat §5).
3. Dashboard dapat dikerjakan kolaboratif oleh 3 orang tanpa bergantung pada struktur folder/laptop siapa pun (pakai `.env`, dokumentasi setup jelas, konvensi Laravel standar).

## 3. Ruang Lingkup

**In scope (MVP):**
- Dashboard utama (ringkasan statistik).
- Halaman topik riset dominan.
- Halaman peta keahlian dosen.
- Halaman profil dosen (detail per dosen).
- Halaman visualisasi jaringan kolaborasi dosen.
- Halaman rekomendasi kolaborasi dosen (menampilkan hasil `recommendations` yang sudah dihitung Python — bukan menghitung ulang similarity di Laravel).
- Filter global: prodi, bidang AI/keilmuan, tahun publikasi, kelompok keahlian.
- Export data ke Excel/PDF.
- Desain visual mengikuti identitas Telkom University (lihat §7).

**Out of scope (untuk fase awal, bisa jadi fase lanjutan):**
- Proses scraping/parsing/embedding — tetap di repo Python, tidak diduplikasi ke Laravel.
- Perhitungan rekomendasi kolaborasi (vector similarity) — tetap dilakukan Python (`recommendation/recommender.py`), Laravel hanya menampilkan.
- Fitur edit data langsung dari dashboard (mis. "Manage Scopus Link" di prototype) — opsional, didiskusikan ulang, butuh auth kalau dipakai.
- Multi-fakultas / multi-tenant (scope saat ini murni FIF).

---

## 4. Arsitektur & Sumber Data — Keputusan Final

**Diputuskan: Opsi A — koneksi langsung ke DB yang sama, dan database menggunakan PostgreSQL biasa (bukan Supabase).**

Laravel connect langsung (read-mostly) ke instance **PostgreSQL** yang sama dipakai scraper Python, lewat driver `pgsql` bawaan Laravel. Tidak ada layer Supabase (auth/storage/realtime/API bawaan Supabase) yang dipakai — murni PostgreSQL polos + ekstensi `pgvector` untuk kebutuhan Python, di-hosting sendiri (self-hosted, mis. VPS, atau Postgres managed service non-Supabase).

Implikasi:
- ✅ Tidak ada duplikasi data / proses sync tambahan — data selalu up-to-date otomatis setiap kali Python re-run `save_to_db.py`.
- ✅ Setup lebih cepat untuk tim kecil dan timeline KP yang terbatas.
- ✅ Tidak bergantung pada layanan pihak ketiga (Supabase) — kalau nanti pindah host Postgres, tinggal ganti `DATABASE_URL`/`.env`, tidak ada dependency ke fitur khusus Supabase.
- ⚠️ Kolom `vector(384)` di tabel `embeddings` tidak perlu diakses Laravel sama sekali (biarkan di luar model Eloquent) — dashboard cukup baca tabel relasional biasa (`lecturers`, `profiles`, `publications`, `keywords`, `research_interests`, `coauthors`, `recommendations`, `collaborations`).
- ⚠️ Ekstensi `vector` (pgvector) tetap harus aktif di instance PostgreSQL yang dipakai (dibutuhkan tabel `embeddings` milik Python), pastikan host Postgres pilihan tim mendukung install ekstensi ini.
- ⚠️ Kredensial DB PostgreSQL harus dibagikan aman ke semua anggota tim via `.env` masing-masing (jangan commit `.env`).
- 📌 Repo scraper (`Telkom-University-Lecturer-Scraper-and-Dashboard-Prototype`) saat ini menyebut "PostgreSQL/Supabase" di README sebagai opsi hosting yang pernah dipakai — itu repo terpisah/eksternal, di luar scope perubahan sesi ini. Yang perlu dipastikan tim: instance Postgres yang **benar-benar dipakai bersama untuk development/production dashboard** adalah PostgreSQL biasa, bukan Supabase. Kalau Python saat ini masih nunjuk ke Supabase, itu perlu dipindah lebih dulu (di luar cakupan PRD dashboard ini, tapi jadi prasyarat M1).

*(Opsi B — sinkronisasi berkala ke DB terpisah milik Laravel — sempat dipertimbangkan tapi tidak dipakai, karena menambah kompleksitas sync yang tidak sepadan untuk scope dan timeline KP ini.)*

> **Update:** mekanisme *konkret* integrasi data hasil scraping ke dashboard — apakah otomatis connect live ke DB Python (sesuai Opsi A di atas) atau data ditambahkan/di-import manual oleh tim — **masih dibahas dan sengaja ditunda dulu**. Supaya pengembangan Laravel tidak terblokir menunggu keputusan itu, tahap awal pembangunan dashboard akan jalan dengan **migration + seeder lokal** yang meniru skema tabel Python (lihat §6), diisi data contoh secukupnya untuk pengembangan UI. Begitu keputusan integrasi final, tinggal disambungkan sesuai Fase 7 di [ROADMAP.md](./ROADMAP.md).
>
> **Update lagi:** sambil menunggu keputusan final di atas, sudah dijalankan *trial* import satu kali dari `data/input/Keilmuan Dosen FIF.xlsx` (repo scraper) lewat command `php artisan import:lecturers {path}` — ngisi identitas & klasifikasi 183 dosen asli (bukan cuma 9 sample), tapi metrik sitasi/publikasi/kolaborasi/rekomendasi tetap kosong untuk 174 dosen baru (data itu belum ada di luar hasil scraping asli). Ini BUKAN keputusan 7.1, cuma preview sekali jalan. Detail lengkap di `docs/ROADMAP.md` bagian "Trial Import".

---

## 5. Pemetaan Fitur Wajib (dari dokumen tugas KP) → Halaman Laravel

| Halaman (sesuai dok. tugas) | Fungsi | Sumber tabel |
|---|---|---|
| **Dashboard Utama** | Ringkasan jumlah dosen, publikasi, bidang AI, kolaborasi | `lecturers`, `publications`, `collaborations`, `lecturers.ai_categories` |
| **Topik Dominan** | Topik riset AI paling banyak muncul | `lecturers.ai_categories` (jsonb), `keywords`, `research_interests` |
| **Peta Keahlian Dosen** | Dosen dikelompokkan per bidang keahlian / kelompok keahlian | `lecturers.field`, `lecturers.research_group`, `lecturers.study_program` |
| **Profil Dosen** | Publikasi & keyword tiap dosen, metrik SINTA, link Scopus/Scholar/ORCID | `lecturers`, `profiles`, `publications`, `keywords`, `research_interests`, `lecturers.sinta_metrics` |
| **Kolaborasi** | Jejaring kerja sama antar dosen (graph) | `collaborations`, `coauthors` |
| **Rekomendasi Kolaborasi** | Rekomendasi pasangan dosen untuk riset AI | `recommendations` |

Filter global (query-string based, konsisten di semua halaman yang relevan): `study_program`, `field`/`ai_category`, `publication_year`, `research_group`.

Export: tombol **Export Excel** dan **Export PDF** minimal tersedia di halaman Dashboard Utama, Profil Dosen, dan Kolaborasi (daftar).

> Catatan: prototype Streamlit sudah mengimplementasikan versi awal dari hampir semua ini (3 tab: Lecturer Profiles, FIF Research Statistics, Collaboration Network) — bisa dipakai sebagai referensi *logic* query, bukan referensi UI/UX final.

---

## 6. Data Model (ringkasan Eloquent, Opsi A)

Skema database mengikuti persis `schema.sql`/`database_details.md` sisi Python (lihat Fase 3.1–3.2 di `docs/ROADMAP.md`). Nama tabel (`lecturers`, `profiles`, dst.) semuanya sudah cocok dengan konvensi penamaan otomatis Eloquent, jadi tidak ada model yang perlu men-set `$table` manual.

```
Lecturer (lecturers)
├── hasMany   Profile (profiles)
├── hasMany   Publication (publications)
├── hasMany   Keyword (keywords)
├── hasMany   ResearchInterest (research_interests)
├── hasMany   Coauthor (coauthors)
├── hasMany   Recommendation (recommendations, via lecturer_id) — recommendationsGiven()
├── hasMany   Recommendation (recommendations, via recommended_lecturer_id) — recommendationsReceived()
├── hasMany   Collaboration (collaborations, via lecturer_id_1) — collaborationsAsFirst()
└── hasMany   Collaboration (collaborations, via lecturer_id_2) — collaborationsAsSecond()
```

`collaborations` **bukan** `belongsToMany` biasa: tabel ini cuma punya satu baris per pasangan dosen (`lecturer_id_1 < lecturer_id_2`, dijamin di sisi Python), jadi kolaborator seorang dosen bisa muncul di salah satu sisi FK saja. `Lecturer` punya method helper `collaborations()` yang menggabungkan `collaborationsAsFirst` + `collaborationsAsSecond` supaya "semua kolaborasi dosen X" bisa diambil dari sisi manapun dia berada.

`Recommendation` dan `Collaboration` masing-masing juga punya relasi `belongsTo` balik ke `Lecturer` (`lecturer()`/`recommendedLecturer()` dan `lecturerOne()`/`lecturerTwo()`).

Kolom penting di `lecturers` untuk UI: `name_with_title`, `lecturer_code`, `study_program`, `research_group` (CITI/DSIS/SEAL), `academic_rank`, `field`, `photo`, `citation_count`, `h_index`, `i10_index`, `ai_categories` (di-cast `array`), `sinta_metrics` (di-cast `array`, per-platform citation/h-index/i10/article/g-index).

Tabel `embeddings` **tidak dipetakan ke Eloquent** — tidak dipakai dashboard.

---

## 7. Referensi Desain — Telkom University

Sudah dicek langsung ke `telkomuniversity.ac.id` (screenshot + inspeksi computed style). Karakteristik yang perlu diikuti:

**Palet warna (hasil inspeksi):**
| Peran | Hex | Sumber |
|---|---|---|
| Primary / brand red | `#9F1521` – `#AC000F` – `#C51626` | top bar, divider bar, tombol utama, heading aksen |
| Primary dark (hover/emphasis) | `#8F131E` | state hover tombol/link |
| Secondary (aksen sekunder) | `#002147` (navy) | dipakai terbatas, bisa jadi warna sekunder chart |
| Neutral background | `#FFFFFF`, `#F9F9F9`, `#F5F5F5` | section background selang-seling |
| Neutral text | `#222222` (heading), `#444444`/`#666666` (body) | tipografi |
| Border/garis halus | `#D0D6DD`, `#D9D6D6` | pembatas card/table |

**Pola UI yang khas dari situs TelU (untuk direplikasi di dashboard, bukan disalin identik):**
- Top utility bar tipis warna merah (link sekunder: Direktori, Berita, dsb.) di atas navigasi utama.
- **Navigasi utama dashboard pakai sidebar kiri** (bukan navbar horizontal ala situs TelU) — keputusan diambil setelah Fase 5 selesai, atas permintaan langsung supaya menu (Dashboard, Topik Dominan, dst.) lebih mudah diakses konsisten di semua halaman. Sidebar statis di desktop, jadi drawer geser dari kiri di mobile. Detail implementasi & verifikasi di `docs/ROADMAP.md` bagian "Perubahan Desain: Navbar → Sidebar".
- Section divider berupa bar merah horizontal tebal sebagai pemisah antar section.
- Background dekoratif garis geometris tipis abu-abu (subtle, tidak ramai) di beberapa section — opsional, gunakan sangat minimal di dashboard (mis. hanya di header halaman) agar tidak mengganggu keterbacaan chart/tabel.
- Heading section pakai warna merah brand + subheading abu-abu di bawahnya.
- Card berbasis icon + judul + deskripsi singkat, banyak whitespace, shadow tipis.
- Tipografi sans-serif bersih. Situs asli memuat font custom yang gagal termuat saat inspeksi (fallback Arial/Helvetica) — **diputuskan pakai Inter** (bukan Poppins) sebagai pengganti modern, karena dashboard ini memprioritaskan keterbacaan data padat (tabel, angka, filter) dibanding gaya heading marketing yang lebih playful ala Poppins. Terpasang self-hosted (400/500/600/700) lewat `laravel-vite-plugin/fonts`, lihat `docs/ROADMAP.md` Fase 2.2.

**Adaptasi ke dashboard (bukan situs marketing):**
- Prioritas keterbacaan data > dekorasi. Pakai merah brand untuk: navbar/topbar, tombol primer, active state filter/tab, aksen judul KPI card, dan warna utama pada chart kategori pertama.
- Untuk chart dengan banyak kategori (mis. pie topik riset, network graph 3 research group), tetap pertahankan skema warna fungsional dari prototype (CITI merah, DSIS hijau, SEAL biru) karena sudah dipakai tim sebelumnya dan konsisten dengan legend yang familiar — merah brand TelU tetap dominan di elemen UI (bukan dipaksakan ke semua data-viz).
- Rekomendasi framework CSS: **Tailwind CSS** dengan custom theme color `telu-red: #9F1521` (dan varian) supaya konsisten dipakai berulang tanpa hardcode hex di banyak tempat.

---

## 8. Tech Stack yang Disarankan

| Layer | Pilihan | Alasan |
|---|---|---|
| Backend framework | **Laravel 13.x (PHP 8.3+)** | Diminta user; versi stabil terbaru saat scaffold dibuat (Juli 2026); ekosistem matang untuk dashboard CRUD/read-heavy. |
| Database driver | `pgsql` (native Laravel) ke instance **PostgreSQL** yang sama dengan scraper (bukan Supabase) | Selaras keputusan Opsi A di §4; hindari duplikasi data & dependency ke layanan pihak ketiga. |
| Templating/UI | **Blade** + **Tailwind CSS** + **Alpine.js** | Cukup untuk dashboard read-mostly dengan filter; tidak perlu build SPA penuh. Tailwind mempermudah replikasi palet TelU secara konsisten. |
| Charting | **Chart.js** (terpasang Fase 5.1) | Untuk bar/line/pie (tren publikasi per tahun, distribusi bidang AI, prodi, kelompok keahlian) — setara Plotly di prototype. |
| Network graph | **vis-network** (terpasang Fase 5.2) | Menggantikan NetworkX+Plotly di prototype untuk visualisasi jaringan kolaborasi, node warna per research group. Catatan: CSS-nya besar (±220KB) — kalau ukuran bundle jadi masalah, D3.js bisa jadi alternatif lebih ringan di kemudian hari. |
| Asset bundling | **Vite** (default Laravel 13) | Standar Laravel terbaru. |
| Unit testing | **PHPUnit** (default scaffold, sudah terpasang) | Keputusan tim: pakai bawaan, tidak ganti ke Pest — tidak ada setup tambahan. |
| Export Excel | **maatwebsite/excel** (terpasang Fase 6.2) | Library Laravel paling umum untuk export xlsx. |
| Export PDF | **barryvdh/laravel-dompdf** (terpasang Fase 6.3) | Generate PDF dari view Blade terpisah (`layouts/pdf.blade.php`) — dompdf tidak proses CSS hasil build Vite/Tailwind. |
| Auth (opsional, jika ada fitur admin/edit) | **Laravel Breeze** | Ringan, cukup untuk KP; hanya dipakai kalau tim putuskan ada fitur edit (mis. edit link Scopus seperti di prototype). |
| Versioning & kolaborasi | Git + GitHub (`Ardavaa/keluarga-kp`) | Repo sudah ada, remote sudah ter-setup. |
| Environment config | `.env` (Laravel) + `.env.example` di-commit (seperti pola di repo Python) | Supaya kredensial DB tidak hardcode & tidak bergantung path/laptop pribadi. |

**Kenapa bukan Inertia+Vue/React atau Livewire?** Untuk scope KP dan tim yang mengerjakan bareng, Blade+Alpine+Tailwind meminimalkan kompleksitas build tool dan kurva belajar dibanding Inertia SPA. Ini rekomendasi default, bukan keharusan — kalau tim lebih nyaman dengan **Livewire** (biar filter reaktif tanpa nulis banyak JS/AJAX manual) atau **Inertia + Vue**, keduanya valid, tinggal didiskusikan sebelum mulai coding karena akan mempengaruhi struktur folder `resources/`.

---

## 9. Non-Functional Requirements

- **Portabilitas tim:** semua konfigurasi lewat `.env`; tidak ada path absolut hardcode di kode maupun dokumentasi setup. `README.md` di `keluarga-kp` harus berisi langkah setup dari clone → `composer install` → `.env` → `npm install && npm run dev` → `php artisan serve`, yang bisa diikuti siapa pun tanpa asumsi OS/folder tertentu.
- **Konsistensi dengan repo Python:** pola `.gitignore` disamakan (secrets, cache, dependency folder tidak ikut commit). `CLAUDE.md`/`AGENT.md`/`AGENTS.md`/`.claude/` sudah ditambahkan ke `.gitignore` di kedua repo agar file instruksi AI asisten lokal tidak ikut ke GitHub.
- **Responsiveness:** dashboard harus tetap terbaca di layar laptop standar (1280–1440px) dan idealnya tablet; mobile-first tidak wajib tapi jangan sampai rusak total di layar kecil.
- **Performa query:** manfaatkan index yang sudah ada di skema Postgres (`idx_*_lecturer_id`); hindari N+1 query Eloquent (pakai eager loading `with()`).
- **Keamanan:** kredensial DB PostgreSQL tidak boleh masuk repo; kalau butuh fitur edit (auth), jangan expose endpoint tulis tanpa autentikasi.

---

## 10. Rencana Bertahap (Milestone Usulan)

1. **M0 — Kesepakatan tim:** finalisasi §8 (Blade+Alpine vs Livewire/Inertia) dan sisa Open Questions §11. (§4 sudah diputuskan: Opsi A + PostgreSQL murni.)
2. **M1 — Prasyarat infra & koneksi data:** pastikan instance PostgreSQL bersama (bukan Supabase) sudah tersedia & ekstensi `pgvector` aktif, lalu init project Laravel di `keluarga-kp`, setup `.env`, koneksi DB, model Eloquent read-only ke skema existing, verifikasi bisa query 161 dosen.
3. **M2 — Layout & desain dasar:** Tailwind theme warna TelU, layout navbar/sidebar, komponen kartu KPI.
4. **M3 — Halaman inti:** Dashboard Utama, Peta Keahlian, Profil Dosen (paling banyak konsumsi data relasi).
5. **M4 — Visualisasi lanjutan:** Topik Dominan (chart), Kolaborasi (network graph), Rekomendasi Kolaborasi.
6. **M5 — Filter global & export Excel/PDF.**
7. **M6 — Polish UI/UX, QA lintas browser, dokumentasi README & serah terima.**

---

## 11. Open Questions untuk Tim (perlu jawaban sebelum/saat M0–M1)

1. Stack frontend detail: Blade+Alpine (rekomendasi) vs Livewire vs Inertia+Vue?
2. Apakah fitur edit data (mis. edit link Scopus, seperti di prototype Streamlit) tetap dibawa ke Laravel? Kalau ya, perlu auth (siapa yang boleh edit?).
3. Instance PostgreSQL bersama akan di-host di mana (VPS mana, self-managed atau managed Postgres non-Supabase)? Ini prasyarat M1 karena §4 mengasumsikan Python & Laravel connect ke instance yang sama.
4. Target deployment aplikasi Laravel: hosting kampus, VPS, atau platform seperti Railway/Render? (Pastikan platform pilihan bisa reach instance PostgreSQL di atas.)
5. Siapa pemegang kredensial PostgreSQL yang akan dibagikan ke anggota tim untuk development lokal?

---

## 12. Referensi

- Repo data: `Telkom-University-Lecturer-Scraper-and-Dashboard-Prototype/README.md`, `database_details.md`, `schema.sql`, `database/models.py`, `dashboard.py`.
- Spreadsheet acuan: `Keilmuan Dosen FIF.xlsx` (kolom: NAMA, KODE, PROGRAM STUDI, Kelompok Keahlian Baru, JFA, Keilmuan).
- Dokumen tugas: `Tugas KP Kelompok 1 Syahdan .docx.pdf`.
- Referensi desain: `telkomuniversity.ac.id` (diinspeksi langsung untuk palet warna & pola UI, per Juli 2026).
