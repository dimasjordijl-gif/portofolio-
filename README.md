# Portfolio SQLite — Dimas Jordi Jimi Issaputra

Website portfolio personal branding menggunakan **PHP Native + SQLite + Bootstrap 5**.

## Keunggulan Versi SQLite
- ✅ **Zero config** — tidak perlu install MySQL/MariaDB
- ✅ **Database otomatis dibuat** saat pertama kali diakses
- ✅ **Portabel** — cukup copy seluruh folder, langsung jalan
- ✅ **Cocok untuk hosting shared / VPS sederhana**
- ✅ **Admin panel** bawaan untuk edit konten via browser

## Struktur File

```
portfolio-sqlite/
├── index.php          # Halaman utama portfolio
├── admin.php          # Panel admin (tambah/hapus konten)
├── includes/
│   ├── config.php     # Koneksi SQLite + auto-setup schema & data
│   └── functions.php  # Fungsi query database
├── data/
│   └── portfolio.db   # ← Dibuat otomatis saat pertama akses
└── README.md
```

## Cara Setup

### 1. Pastikan PHP punya ekstensi SQLite
```bash
php -m | grep sqlite   # harus muncul "sqlite3" atau "pdo_sqlite"
```
Jika belum aktif, di `php.ini` uncomment:
```
extension=pdo_sqlite
extension=sqlite3
```

### 2. Jalankan Server
```bash
# PHP built-in server
php -S localhost:8080

# Atau taruh di htdocs (XAMPP) / www (WAMP/Laragon)
```

Buka: `http://localhost:8080`

Database `data/portfolio.db` akan **dibuat otomatis** dengan semua data lengkap.

### 3. Akses Admin Panel
```
http://localhost:8080/admin.php
```
Dari sini bisa tambah/hapus: Proyek, Pengalaman Kerja, Skills, dan edit Profil — langsung dari browser, tanpa perlu SQL manual.

> ⚠️ **PENTING:** Lindungi `admin.php` dengan password atau hapus di production!

## Keamanan di Production

Tambahkan password sederhana di awal `admin.php`:
```php
$adminPass = 'password_rahasia_anda';
if (!isset($_COOKIE['admin_auth']) || $_COOKIE['admin_auth'] !== md5($adminPass)) {
    if ($_POST['pass'] ?? '' === $adminPass) {
        setcookie('admin_auth', md5($adminPass), time()+3600);
    } else {
        die('<form method="POST"><input name="pass" type="password" placeholder="Password"><button>Masuk</button></form>');
    }
}
```

Pastikan folder `data/` tidak dapat diakses langsung dari browser dengan menambah `.htaccess`:
```
# data/.htaccess
Deny from all
```

## Update Konten via SQL Langsung

```bash
# SQLite CLI
sqlite3 data/portfolio.db

# Contoh perintah
UPDATE profile SET tagline = 'Tagline baru...' WHERE id = 1;
INSERT INTO projects (title, description, tools, outcome, category, featured, sort_order)
VALUES ('Proyek Baru', 'Deskripsi...', 'Python, SQL', 'Hasil...', 'Analytics', 1, 99);
.quit
```
