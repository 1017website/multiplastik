# Multi Plastik CMS — Laravel 12

CMS untuk website Multi Plastik. Semua konten (slider, brand, kategori, produk, news, pengaturan, SEO, analytics, iklan) bisa diedit dari panel admin.

---

## Isi Paket

```
app/
  Http/Controllers/Admin/   → semua controller panel admin
  Http/Controllers/Site/    → controller frontend
  Http/Middleware/          → AdminOnly, TrackVisit (analytics)
  Models/                   → Brand, Category, Product, News, Slide, Promo, SiteSetting, PageVisit, User
  Providers/                → AppServiceProvider, ViewServiceProvider
  helpers.php               → fungsi setting(), media_url(), wa_link()
bootstrap/
  app.php                   → registrasi middleware
  providers.php             → registrasi provider
database/
  migrations/               → 1 file migrasi semua tabel
  seeders/                  → data awal dari template (brand, produk, news, settings)
public/
  css/site.css              → CSS frontend (dari template)
  uploads/                  → folder upload gambar
resources/views/
  admin/                    → semua halaman panel admin
  site/                     → semua halaman frontend (home, brand, produk, news, dll)
routes/
  web.php                   → semua route
.env.example
composer.json
```

---

## Cara Pasang ke Laravel 12 Fresh

### 1. Buat project Laravel 12 baru (jika belum ada)
```bash
composer create-project laravel/laravel multiplastik
cd multiplastik
```

### 2. Copy semua file dari paket ini
Timpa / merge file ke project. Yang penting:
- `app/` → merge (jangan hapus file bawaan, cukup tambah/timpa yang ada di paket)
- `bootstrap/app.php` dan `bootstrap/providers.php` → timpa
- `database/migrations/` → copy file migrasi
- `database/seeders/` → timpa DatabaseSeeder + copy ProductCatalogSeeder
- `public/css/`, `public/uploads/` → copy
- `resources/views/` → copy folder `admin` dan `site`
- `routes/web.php` → timpa
- `composer.json` → timpa (atau tambahkan bagian `autoload.files` saja)

### 3. Setup database
Buat database MySQL bernama `multiplastik`, lalu:
```bash
cp .env.example .env        # kalau belum ada .env
php artisan key:generate
```
Edit `.env` sesuaikan `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

### 4. Reload autoload (penting untuk helpers.php)
```bash
composer dump-autoload
```

### 5. Migrasi + seed data awal
```bash
php artisan migrate --seed
```

### 6. Buat symlink storage (opsional, kalau pakai storage)
Folder upload sudah pakai `public/uploads`, jadi tidak wajib. Pastikan folder writable:
```bash
chmod -R 775 public/uploads
```

### 7. Jalankan
```bash
php artisan serve
```

---

## Akses

| Halaman | URL |
|---|---|
| Website | `http://localhost:8000` |
| Admin Login | `http://localhost:8000/admin/login` |

**Login default:**
- Email: `admin@multiplastik.com`
- Password: `admin123`

> Ganti password setelah login pertama via menu **User Admin**.

---

## Fitur Panel Admin

- **Dashboard** — ringkasan kunjungan + grafik 7 hari
- **Analytics** — kunjungan per hari, device, referrer, dan traffic iklan (UTM)
- **Hero Slider** — kelola slide di homepage
- **Promo Bar** — teks marquee berjalan
- **News & Artikel** — CRUD artikel (konten HTML)
- **Brand / Kategori / Produk** — katalog bertingkat, dengan spesifikasi & gallery
- **Site Settings** — Umum/SEO, Kontak, Section Tentang, Keunggulan, Hero Stats, Embed Sosmed, Analytics, Iklan
- **User Admin** — kelola akun admin

---

## Analytics, Meta Ads & Google Ads

Masuk **Admin → Site Settings**:

- Tab **Analytics**: isi Google Analytics 4 (`G-XXX`), Google Tag Manager (`GTM-XXX`), Meta Pixel ID, TikTok Pixel ID. Script otomatis terpasang di semua halaman.
- Tab **Iklan**: isi Google Ads Conversion ID (`AW-XXX`), atau tempel custom script.

**Tracking pengunjung internal** (tanpa tool eksternal) sudah otomatis lewat middleware `TrackVisit` — tercatat di menu Analytics, termasuk parameter UTM dari link iklan:
```
https://multiplastik.com/?utm_source=meta&utm_medium=cpc&utm_campaign=promo-2025
```

---

## Catatan Teknis

- **Upload gambar**: setiap form gambar bisa upload file ATAU paste URL (mis. Cloudinary). Data seed pakai URL Cloudinary yang sudah ada.
- **Spesifikasi produk**: format per baris `Label|Value` (contoh: `Ukuran|12 Oz`).
- **Link tombol slider**: `nav:brands`, `nav:brand:hok-cup`, `wa`, atau URL biasa.
- **Cache settings**: nilai settings di-cache; otomatis ter-clear saat disimpan dari admin.
- **Frontend multi-page**: tiap brand/kategori/produk/artikel punya URL sendiri (SEO friendly).

URL produk: `/brand/{brand}/{kategori}/{produk}`
Contoh: `/brand/hok-cup/gelas-natural/hc-natural-12oz`
