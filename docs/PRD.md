# PRD — Dashboard Peta Keahlian & Kolaborasi Riset Dosen FIF (Telkom University)

| | |
|---|---|
| **Repo** | `keluarga-kp` (dashboard, Laravel) |
| **Repo sumber data** | `Telkom-University-Lecturer-Scraper-and-Dashboard-Prototype` (scraper Scopus) |
| **Tim** | Kelompok 1 KP — Syahdan Rizqi Ruhendy (103012330308), Muhammad Ghozy Abdurrahman (103012330264), Muhammad Karov Ardava Barus (103052300001) |
| **Status dokumen** | Final — arsitektur data, peran pengguna, dan sumber data sudah ditetapkan |
| **Catatan portabilitas** | Dokumen ini sengaja tidak menyebut path folder lokal siapa pun. Semua path di bawah bersifat relatif terhadap root masing-masing repo agar konsisten di laptop anggota tim manapun. |

---

## 1. Latar Belakang

Kelompok 1 mengerjakan KP dengan judul **"Dashboard Visualisasi Topik Riset dan Peta Keahlian Dosen untuk Mendukung Kolaborasi AI di FIF"**. Tujuannya membantu Satgas AI FIF memetakan kekuatan riset, topik dominan, klaster keahlian dosen, dan peluang kolaborasi antar dosen di Fakultas Informatika (FIF) Telkom University.

Sistem terdiri dari dua bagian yang saling melengkapi:
- **Pipeline scraping** (repo `Telkom-University-Lecturer-Scraper-and-Dashboard-Prototype`) — mengambil data publikasi, sitasi, profil, dan co-authorship dosen FIF dari **Scopus**, lalu menstrukturkannya dengan bantuan parsing AI (Gemini) dan menyimpannya ke database.
- **Dashboard web** (repo `keluarga-kp`, Laravel) — lapisan presentasi/produk akhir yang menyajikan data tersebut dengan UX yang baik dan sesuai identitas visual Telkom University.

Data acuan klasifikasi keahlian dosen mengikuti spreadsheet resmi `Keilmuan Dosen FIF.xlsx` yang memetakan Prodi → Kelompok Keahlian (CITI / DSIS / SEAL) → Keilmuan per dosen.

**Tugas KP ini secara spesifik meminta output akhir berbentuk dashboard**, sehingga scraper dianggap sebagai *data pipeline* (system of record) dan **Laravel dashboard di repo `keluarga-kp` menjadi produk akhir** yang dinilai.

---

## 2. Tujuan

1. Membangun dashboard web (Laravel) yang menyajikan data dosen FIF hasil scraping dengan UX yang baik dan sesuai identitas visual Telkom University.
2. Memenuhi seluruh fitur wajib yang diminta di dokumen tugas KP (lihat §5).
3. Menyediakan panel Admin untuk mengoreksi data yang salah, tanpa mengganggu proses scraping otomatis (lihat §3.1 & §4).
4. Dashboard dapat dikerjakan kolaboratif oleh 3 orang tanpa bergantung pada struktur folder/laptop siapa pun (pakai `.env`, dokumentasi setup jelas, konvensi Laravel standar).

---

## 3. Ruang Lingkup

**In scope (MVP):**
- Dashboard utama (ringkasan statistik).
- Halaman topik riset dominan.
- Halaman peta keahlian dosen.
- Halaman profil dosen (detail per dosen).
- Halaman visualisasi jaringan kolaborasi dosen.
- Halaman rekomendasi kolaborasi dosen (menampilkan hasil `recommendations` yang sudah dihitung di sisi scraper — bukan menghitung ulang similarity di Laravel).
- Filter global: prodi, bidang AI/keilmuan, tahun publikasi, kelompok keahlian.
- Export data ke Excel/PDF.
- Desain visual mengikuti identitas Telkom University (lihat §7).
- **Panel Admin untuk mengoreksi data dosen** (lihat §3.1). Wajib login (auth), UI mengikuti referensi desain yang sama dengan dashboard publik (§7).

**Out of scope (untuk fase awal, bisa jadi fase lanjutan):**
- Proses scraping/parsing — tetap di repo scraper, tidak diduplikasi ke Laravel.
- Perhitungan rekomendasi kolaborasi (similarity antar dosen) — tetap dilakukan di sisi scraper, Laravel hanya menampilkan hasilnya.
- Multi-fakultas / multi-tenant (scope saat ini murni FIF).
- Self-registration publik / multi-level role kompleks — cukup dua peran (User & Admin), akun admin dibuat manual/seed (lihat §3.1).

### 3.1 Peran Pengguna (User vs Admin)

Ada **dua peran** di sistem:

| Peran | Akses | Fungsi utama |
|---|---|---|
| **User (publik/viewer)** | Read-only — semua halaman dashboard di §5 | Melihat & mengeksplor peta keahlian, topik, profil, kolaborasi, rekomendasi; filter & export. **Tidak perlu login.** |
| **Admin** | Read-write — semua halaman User **+** panel admin | **Mengoreksi/mengedit data dosen** ketika ada data yang salah (mis. judul publikasi keliru). **Wajib login.** |

Prinsip peran:
- **Data tayang otomatis tanpa approval.** Begitu scraper selesai menulis data ke database, data langsung tampil di dashboard publik — tidak ada tahap review/persetujuan dari siapa pun sebelum tayang.
- **Admin bersifat reaktif, bukan gatekeeper.** Admin tidak me-review data sebelum tayang. Admin baru bertindak kalau ada laporan/protes dari publik bahwa suatu data salah — lalu Admin login, mengoreksi field yang bermasalah lewat panel admin.
- Halaman dashboard publik tetap bisa diakses tanpa login (User). Panel admin & semua endpoint tulis dilindungi middleware auth — hanya Admin.
- **Panel admin memakai referensi desain yang sama dengan dashboard publik** (layout sidebar, palet TelU, komponen kartu/tabel/form yang konsisten — lihat §7). Bukan template admin generik terpisah, supaya pengalaman visual satu kesatuan.
- Akun Admin dibuat lewat seeder/command (bukan registrasi publik) — jumlah admin sedikit & terkontrol (anggota tim / Satgas AI FIF).

Agar koreksi Admin tidak tertimpa saat scraper menulis ulang data, dipakai mekanisme penanda **override per field** — dijelaskan di §4.3.

---

## 4. Arsitektur & Sumber Data

### 4.1 Sumber data scraping
Data publikasi, sitasi, profil, dan co-authorship dosen diambil **dari Scopus**. Scraping dilakukan dengan **Playwright**, lalu teks hasil scraping distrukturkan menjadi field yang rapi menggunakan **Gemini API** (parsing/cleaning berbasis AI).

### 4.2 Database: MySQL tunggal
- Sistem memakai **satu database MySQL** yang dipakai bersama oleh scraper maupun dashboard.
- Scraper menulis hasil scraping langsung ke MySQL. Laravel dashboard membaca dari database yang sama lewat driver `mysql` bawaan Laravel. Tidak ada database perantara dan tidak ada proses sinkronisasi antar-database.
- **Perhitungan rekomendasi kolaborasi** (similarity antar dosen berdasarkan kemiripan bidang/keyword) dilakukan **in-memory di sisi scraper** (mis. `numpy`/`scikit-learn`). Yang disimpan ke MySQL hanyalah **hasil akhirnya** — tabel relasional biasa (`recommendations` berisi pasangan dosen + skor). Untuk skala data proyek ini (±150–200 dosen), komputasi ini ringan dan tidak menimbulkan masalah performa.

### 4.3 Mekanisme tulis-data (scraper otomatis + koreksi Admin)
Karena scraper dan Admin (§3.1) sama-sama menulis ke tabel yang sama, dipakai penanda **override per field** supaya keduanya tidak saling menimpa:

- Setiap kali scraper menulis/meng-update baris dosen, ia **melewati field yang sudah ditandai `is_overridden = true`** — field itu dianggap sudah dikoreksi manual oleh Admin dan tidak boleh ditimpa otomatis.
- Ketika Admin mengoreksi sebuah field lewat panel admin, field itu otomatis ditandai `is_overridden = true`.
- Admin bisa melepas tanda override kapan pun kalau field tersebut ingin kembali ikut auto-update dari scraper.
- **Sudah diputuskan**: struktur penyimpanan flag override pakai **tabel terpisah** `lecturer_field_overrides` (`lecturer_id`, `field`, unique gabungan keduanya) — bukan kolom boolean per-field di `lecturers`. Dipilih karena lebih fleksibel (tidak perlu migration baru tiap ada field baru yang boleh dikoreksi) dan lebih ringkas (menghindari puluhan kolom `*_overridden` di tabel `lecturers`).

### 4.4 Ringkasan alur data (end-to-end)
1. **Scraping** — scraper ambil data dari Scopus (Playwright), parsing/cleaning (Gemini API).
2. **Rekomendasi** — similarity antar dosen dihitung in-memory, hasilnya (pasangan + skor) disiapkan untuk disimpan.
3. **Simpan** — scraper menulis semua data ke MySQL, melewati field yang sudah ditandai `is_overridden`.
4. **Tayang** — Laravel (Eloquent, read-only) membaca MySQL dan merender halaman lewat Blade; data tampil otomatis ke dashboard publik tanpa approval.
5. **Koreksi reaktif** — jika ada laporan data salah, Admin login, mengedit field lewat form, dan field itu ditandai override sehingga aman dari penulisan scraper berikutnya.

Implikasi:
- ✅ Arsitektur sederhana — satu database, tanpa langkah sync/impor antar-database.
- ✅ Tidak butuh ekstensi database khusus atau instalasi tambahan di hosting.
- ✅ Scraper & Admin bisa berjalan independen tanpa konflik, berkat penanda override per field.
- ⚠️ Perhitungan similarity/rekomendasi dijalankan ulang tiap kali scraper jalan (tidak ada index similarity persisten) — dampaknya diabaikan untuk skala data proyek ini, tapi perlu diperhitungkan kalau jumlah dosen membesar drastis di masa depan.
- ⚠️ Kredensial MySQL harus dibagikan aman ke semua anggota tim via `.env` masing-masing (jangan commit `.env`).

---

## 5. Pemetaan Fitur Wajib (dari dokumen tugas KP) → Halaman Laravel

| Halaman (sesuai dok. tugas) | Fungsi | Sumber tabel |
|---|---|---|
| **Dashboard Utama** | Ringkasan jumlah dosen, publikasi, bidang AI, kolaborasi | `lecturers`, `publications`, `collaborations`, `lecturers.ai_categories` |
| **Topik Dominan** | Topik riset AI paling banyak muncul | `lecturers.ai_categories` (JSON), `keywords`, `research_interests` |
| **Peta Keahlian Dosen** | Dosen dikelompokkan per bidang keahlian / kelompok keahlian | `lecturers.field`, `lecturers.research_group`, `lecturers.study_program` |
| **Profil Dosen** | Publikasi & keyword tiap dosen, metrik sitasi (dari Scopus), link Scopus/Scholar/ORCID | `lecturers`, `profiles`, `publications`, `keywords`, `research_interests`, `lecturers.sinta_metrics` |
| **Kolaborasi** | Jejaring kerja sama antar dosen (graph) | `collaborations`, `coauthors` |
| **Rekomendasi Kolaborasi** | Rekomendasi pasangan dosen untuk riset AI | `recommendations` |
| **Panel Admin — Koreksi Data Dosen** *(Admin only)* | Login, lalu edit data dosen (identitas, klasifikasi, metrik) yang dilaporkan salah lewat form | `lecturers` (+ tabel terkait), `users` (auth) |

Filter global (query-string based, konsisten di semua halaman yang relevan): `study_program`, `field`/`ai_category`, `publication_year`, `research_group`.

Panel Admin (§3.1) berada di grup route terpisah (mis. prefix `/admin`) dengan middleware auth. Tampilannya memakai layout & komponen yang sama dengan dashboard publik (§7) — form input/edit dosen mengikuti gaya kartu, tabel, dan palet TelU yang sudah ada, bukan template admin pihak ketiga.

Export: tombol **Export Excel** dan **Export PDF** minimal tersedia di halaman Dashboard Utama, Profil Dosen, dan Kolaborasi (daftar).

---

## 6. Data Model (ringkasan Eloquent)

Skema database memakai MySQL (mis. kolom bertipe JSON untuk `ai_categories`/`sinta_metrics`, tetap bisa di-cast `array` di Eloquent seperti biasa). Nama tabel (`lecturers`, `profiles`, dst.) mengikuti konvensi penamaan otomatis Eloquent, jadi tidak ada model yang perlu men-set `$table` manual.

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

`collaborations` **bukan** `belongsToMany` biasa: tabel ini cuma punya satu baris per pasangan dosen (`lecturer_id_1 < lecturer_id_2`), jadi kolaborator seorang dosen bisa muncul di salah satu sisi FK saja. `Lecturer` punya method helper `collaborations()` yang menggabungkan `collaborationsAsFirst` + `collaborationsAsSecond` supaya "semua kolaborasi dosen X" bisa diambil dari sisi manapun dia berada.

`Recommendation` dan `Collaboration` masing-masing juga punya relasi `belongsTo` balik ke `Lecturer` (`lecturer()`/`recommendedLecturer()` dan `lecturerOne()`/`lecturerTwo()`).

Kolom penting di `lecturers` untuk UI: `name_with_title`, `lecturer_code`, `study_program`, `research_group` (CITI/DSIS/SEAL), `academic_rank`, `field`, `photo`, `citation_count`, `h_index`, `i10_index`, `ai_categories` (di-cast `array`), `sinta_metrics` (di-cast `array`, berisi metrik sitasi/h-index/i10/artikel/g-index).

Untuk mekanisme koreksi Admin (§4.3), setiap field yang boleh dikoreksi manual perlu penanda `is_overridden` (struktur teknisnya — kolom per-field vs tabel terpisah — lihat §11).

---

## 7. Referensi Desain — Telkom University

Referensi diambil langsung dari `telkomuniversity.ac.id` (screenshot + inspeksi computed style). Karakteristik yang diikuti:

**Palet warna (hasil inspeksi):**
| Peran | Hex | Sumber |
|---|---|---|
| Primary / brand red | `#9F1521` – `#AC000F` – `#C51626` | top bar, divider bar, tombol utama, heading aksen |
| Primary dark (hover/emphasis) | `#8F131E` | state hover tombol/link |
| Secondary (aksen sekunder) | `#002147` (navy) | dipakai terbatas, bisa jadi warna sekunder chart |
| Neutral background | `#FFFFFF`, `#F9F9F9`, `#F5F5F5` | section background selang-seling |
| Neutral text | `#222222` (heading), `#444444`/`#666666` (body) | tipografi |
| Border/garis halus | `#D0D6DD`, `#D9D6D6` | pembatas card/table |

**Pola UI yang diadopsi (direplikasi, bukan disalin identik):**
- **Navigasi utama dashboard pakai sidebar kiri.** Menu (Dashboard, Topik Dominan, Peta Keahlian, Profil Dosen, Kolaborasi, Rekomendasi) diakses konsisten di semua halaman. Sidebar statis di desktop, jadi drawer geser dari kiri di mobile.
- Heading section pakai warna merah brand + subheading abu-abu di bawahnya.
- Card berbasis judul + deskripsi singkat, banyak whitespace, garis pembatas tipis.
- Tipografi sans-serif bersih memakai **Inter** (400/500/600/700), terpasang self-hosted. Inter dipilih karena dashboard memprioritaskan keterbacaan data padat (tabel, angka, filter).
- Gaya visual **flat & bersih**: border 1px sebagai pembatas, tanpa gradient/glow/efek dekoratif berlebih, supaya fokus ke keterbacaan data.

**Adaptasi ke dashboard (bukan situs marketing):**
- Prioritas keterbacaan data > dekorasi. Pakai merah brand untuk: topbar/sidebar aktif, tombol primer, active state filter/tab, aksen judul KPI card, dan warna utama pada chart kategori pertama.
- Untuk chart dengan banyak kategori (mis. pie topik riset, network graph 3 research group), pakai skema warna fungsional per kelompok keahlian (CITI merah, DSIS hijau, SEAL biru) supaya legend konsisten & familiar — merah brand TelU tetap dominan di elemen UI (bukan dipaksakan ke semua data-viz).
- Framework CSS: **Tailwind CSS** dengan custom theme color `telu-red: #9F1521` (dan varian) supaya konsisten dipakai berulang tanpa hardcode hex di banyak tempat.
- **Panel Admin (§3.1) memakai referensi desain yang sama** — sidebar, palet TelU, dan komponen (kartu, tabel, tombol, badge) yang dipakai di dashboard publik dipakai ulang untuk halaman login & form koreksi data. Form input mengikuti gaya flat/bersih (border 1px, tanpa gradient/glow berlebih). Tujuannya admin & viewer terasa satu produk, bukan dua aplikasi berbeda.

---

## 8. Tech Stack

| Layer | Pilihan | Alasan |
|---|---|---|
| Backend framework | **Laravel 13.x (PHP 8.3+)** | Versi stabil terbaru; ekosistem matang untuk dashboard read-heavy + sedikit CRUD (panel admin). |
| Database | **MySQL 8.x** (driver `mysql` native Laravel) | Satu database tunggal untuk scraper & dashboard; didukung luas oleh hosting umum. |
| Templating/UI | **Blade** + **Tailwind CSS** + **Alpine.js** | Cukup untuk dashboard read-mostly dengan filter + form admin; tidak perlu SPA penuh. Tailwind mempermudah replikasi palet TelU secara konsisten. |
| Charting | **Chart.js** | Bar/line/pie (tren publikasi per tahun, distribusi bidang AI, prodi, kelompok keahlian). |
| Network graph | **vis-network** | Visualisasi jaringan kolaborasi, node warna per research group. |
| Asset bundling | **Vite** (default Laravel) | Standar Laravel terbaru. |
| Unit testing | **PHPUnit** (default scaffold) | Bawaan, tanpa setup tambahan. |
| Export Excel | **maatwebsite/excel** | Library Laravel paling umum untuk export xlsx. |
| Export PDF | **barryvdh/laravel-dompdf** | Generate PDF dari view Blade terpisah (`layouts/pdf.blade.php`). |
| Auth (panel Admin §3.1) | **Auth facade & middleware bawaan Laravel** (bukan paket Breeze) | Panel Admin hanya butuh login (tanpa registrasi/verifikasi email/reset password), jadi cukup pakai `Auth`/middleware `auth`+`guest` bawaan Laravel — lebih ringan dari Breeze untuk kebutuhan sesempit ini. Registrasi publik tidak ada route-nya sama sekali — akun Admin lewat seeder. |
| Versioning & kolaborasi | Git + GitHub (`Ardavaa/keluarga-kp`) | Repo & remote sudah ter-setup. |
| Environment config | `.env` + `.env.example` di-commit | Supaya kredensial DB tidak hardcode & tidak bergantung path/laptop pribadi. |

**Sisi scraper** (repo terpisah, konteks): scraping **Playwright**, parsing **Gemini API**, komputasi rekomendasi **Python (numpy/scikit-learn)**, penyimpanan ke **MySQL** (driver mis. `PyMySQL`/`mysql-connector-python`).

**Kenapa Blade+Alpine, bukan Inertia+Vue/React atau Livewire?** Untuk scope KP dan tim yang mengerjakan bareng, Blade+Alpine+Tailwind meminimalkan kompleksitas build tool dan kurva belajar. Ini rekomendasi default; kalau tim lebih nyaman Livewire/Inertia, keduanya valid, tinggal didiskusikan sebelum coding karena mempengaruhi struktur `resources/`.

---

## 9. Non-Functional Requirements

- **Portabilitas tim:** semua konfigurasi lewat `.env`; tidak ada path absolut hardcode di kode maupun dokumentasi setup. `README.md` di `keluarga-kp` harus berisi langkah setup dari clone → `composer install` → `.env` → `npm install && npm run dev` → `php artisan serve`, yang bisa diikuti siapa pun tanpa asumsi OS/folder tertentu.
- **Konsistensi repo:** pola `.gitignore` menjaga secrets, cache, dan dependency folder tidak ikut commit. `CLAUDE.md`/`AGENT.md`/`AGENTS.md`/`.claude/` ditambahkan ke `.gitignore` agar file instruksi AI asisten lokal tidak ikut ke GitHub.
- **Responsiveness:** dashboard harus tetap terbaca di layar laptop standar (1280–1440px) dan idealnya tablet; mobile-first tidak wajib tapi jangan sampai rusak total di layar kecil.
- **Performa query:** manfaatkan index pada foreign key (`idx_*_lecturer_id`); hindari N+1 query Eloquent (pakai eager loading `with()`).
- **Keamanan:** kredensial MySQL tidak boleh masuk repo; endpoint tulis (panel Admin §3.1) wajib di balik middleware `auth`.

---

## 10. Rencana Bertahap (Milestone)

1. **M0 — Kesepakatan tim:** finalisasi §8 (Blade+Alpine vs Livewire/Inertia) dan sisa Open Questions §11.
2. **M1 — Prasyarat infra & koneksi data:** pastikan instance MySQL bersama tersedia, init project Laravel di `keluarga-kp`, setup `.env`, koneksi DB, model Eloquent ke skema, verifikasi bisa query data dosen.
3. **M2 — Layout & desain dasar:** Tailwind theme warna TelU, layout sidebar, komponen kartu KPI.
4. **M3 — Halaman inti:** Dashboard Utama, Peta Keahlian, Profil Dosen.
5. **M4 — Visualisasi lanjutan:** Topik Dominan (chart), Kolaborasi (network graph), Rekomendasi Kolaborasi.
6. **M5 — Filter global & export Excel/PDF.**
7. **M6 — Auth & Panel Admin (§3.1):** autentikasi manual (Auth facade Laravel, bukan Breeze), matikan registrasi publik, seeder akun admin, grup route `/admin` dengan middleware auth, form koreksi data dosen dengan UI mengikuti desain dashboard (§7) + mekanisme penanda override (§4.3).
8. **M7 — Polish UI/UX, QA lintas browser, dokumentasi README & serah terima.**

---

## 11. Open Questions untuk Tim

1. Stack frontend detail: Blade+Alpine (rekomendasi) vs Livewire vs Inertia+Vue?
2. **Sudah diputuskan**: struktur flag override (§4.3) pakai tabel `lecturer_field_overrides`. **Masih perlu dijawab**: siapa saja yang jadi Admin (nama-nama konkret untuk seeder)?
3. Instance MySQL bersama akan di-host di mana (VPS mana, self-managed atau managed MySQL)? Prasyarat M1 karena scraper & Laravel connect ke instance yang sama.
4. Target deployment aplikasi Laravel: hosting kampus, VPS, atau platform seperti Railway/Render? (Pastikan platform pilihan bisa reach instance MySQL di atas.)
5. Siapa pemegang kredensial MySQL yang dibagikan ke anggota tim untuk development lokal?

---

## 12. Referensi

- Repo data: `Telkom-University-Lecturer-Scraper-and-Dashboard-Prototype`.
- Spreadsheet acuan: `Keilmuan Dosen FIF.xlsx` (kolom: NAMA, KODE, PROGRAM STUDI, Kelompok Keahlian Baru, JFA, Keilmuan).
- Dokumen tugas: `Tugas KP Kelompok 1 Syahdan .docx.pdf`.
- Referensi desain: `telkomuniversity.ac.id` (diinspeksi langsung untuk palet warna & pola UI).
