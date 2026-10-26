<?php
// ============================================================
// Database Configuration — SQLite
// ============================================================

// Path ke file database SQLite (di luar folder public jika memungkinkan)
define('DB_PATH', __DIR__ . '/../data/portfolio.db');

define('SITE_TITLE', 'Dimas Jordi Jimi Issaputra — Data Analyst');
define('SITE_DESCRIPTION', 'Portfolio Data Analyst profesional berbasis di Jakarta');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dbPath = DB_PATH;

        // Buat folder data jika belum ada
        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $isNew = !file_exists($dbPath);

        try {
            $pdo = new PDO('sqlite:' . $dbPath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->exec('PRAGMA journal_mode=WAL;');
            $pdo->exec('PRAGMA foreign_keys=ON;');
        } catch (PDOException $e) {
            die('Database error: ' . $e->getMessage());
        }

        // Auto-setup schema + seed jika database baru
        if ($isNew) {
            setupDatabase($pdo);
        }
    }
    return $pdo;
}

function setupDatabase(PDO $pdo): void {
    $pdo->exec("BEGIN;");

    // ── Schema ──────────────────────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS profile (
            id      INTEGER PRIMARY KEY AUTOINCREMENT,
            name    TEXT,
            tagline TEXT,
            bio     TEXT,
            email   TEXT,
            phone   TEXT,
            location TEXT,
            linkedin TEXT,
            github  TEXT,
            years_experience INTEGER,
            updated_at TEXT DEFAULT (datetime('now'))
        );

        CREATE TABLE IF NOT EXISTS skills (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            category    TEXT,
            name        TEXT,
            proficiency INTEGER CHECK(proficiency BETWEEN 0 AND 100),
            icon        TEXT,
            sort_order  INTEGER DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS projects (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            title       TEXT,
            description TEXT,
            tools       TEXT,
            outcome     TEXT,
            category    TEXT,
            featured    INTEGER DEFAULT 0,
            sort_order  INTEGER DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS experience (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            company     TEXT,
            role        TEXT,
            period      TEXT,
            description TEXT,
            sort_order  INTEGER DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS education (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            institution TEXT,
            degree      TEXT,
            period      TEXT,
            notes       TEXT
        );

        CREATE TABLE IF NOT EXISTS certifications (
            id      INTEGER PRIMARY KEY AUTOINCREMENT,
            name    TEXT,
            issuer  TEXT,
            year    INTEGER,
            icon    TEXT
        );
    ");

    // ── Seed Data ────────────────────────────────────────────
    $pdo->exec("
        INSERT INTO profile (name, tagline, bio, email, phone, location, linkedin, github, years_experience) VALUES
        ('Dimas Jordi Jimi Issaputra',
         'Data Analyst · Turning Raw Numbers into Strategic Insight',
         'Saya adalah Data Analyst berpengalaman dengan keahlian dalam mengolah data kompleks menjadi wawasan bisnis yang actionable. Dengan latar belakang statistik dan teknologi informasi, saya membantu organisasi membuat keputusan berbasis data yang tepat dan efisien.',
         'dimasjordijl@email.com',
         '+62 878-3673-4880',
         'Surakarta, Indonesia',
         'linkedin.com/in/dimas-jordi-6b8231445',
         'github.com/dimasjordijl-gif',
         4);

        INSERT INTO skills (category, name, proficiency, icon, sort_order) VALUES
        ('Analytics',       'Python (Pandas, NumPy)', 90, 'bi-code-slash', 1),
        ('Analytics',       'SQL & Database Query',   92, 'bi-database',   2),
        ('Analytics',       'R Programming',          75, 'bi-bar-chart',  3),
        ('Visualization',   'Tableau',                88, 'bi-pie-chart-fill', 4),
        ('Visualization',   'Power BI',               85, 'bi-graph-up-arrow', 5),
        ('Visualization',   'Google Data Studio',     80, 'bi-display',    6),
        ('Tools',           'Excel & Google Sheets',  95, 'bi-file-earmark-spreadsheet', 7),
        ('Tools',           'Jupyter Notebook',       88, 'bi-book',       8),
        ('Tools',           'Git & Version Control',  72, 'bi-git',        9),
        ('Machine Learning','Scikit-learn',            70, 'bi-cpu',        10),
        ('Machine Learning','Statistical Modeling',   82, 'bi-calculator', 11),
        ('Machine Learning','A/B Testing',             78, 'bi-split',      12);

        INSERT INTO projects (title, description, tools, outcome, category, featured, sort_order) VALUES
        ('Customer Churn Prediction Dashboard',
         'Membangun model prediksi churn pelanggan menggunakan machine learning untuk perusahaan telekomunikasi dengan 2 juta pelanggan aktif. Mencakup pipeline ETL, feature engineering, dan dashboard interaktif real-time.',
         'Python, Scikit-learn, SQL, Tableau', 'Mengurangi churn rate sebesar 18% dalam Q1',
         'Machine Learning', 1, 1),
        ('Sales Performance Analytics',
         'Merancang dan mengimplementasikan sistem pelaporan penjualan end-to-end untuk jaringan retail nasional dengan 120+ cabang. Otomatisasi laporan harian, mingguan, dan bulanan.',
         'Power BI, SQL Server, DAX, Excel', 'Menghemat 40 jam/bulan waktu pelaporan manual',
         'Business Intelligence', 1, 2),
        ('Market Basket Analysis — E-commerce',
         'Analisis asosiasi produk menggunakan algoritma Apriori untuk menemukan pola pembelian pelanggan pada platform e-commerce dengan 500K+ transaksi per bulan.',
         'Python, Pandas, MLxtend, Plotly', 'Meningkatkan cross-sell revenue 12%',
         'Analytics', 1, 3),
        ('HR Attrition Analysis',
         'Eksplorasi dan visualisasi data SDM untuk mengidentifikasi faktor-faktor utama penyebab turnover karyawan. Menghasilkan rekomendasi kebijakan berbasis data.',
         'R, ggplot2, Tableau, SPSS', 'Menurunkan attrition rate 9% dalam 6 bulan',
         'Analytics', 0, 4),
        ('COVID-19 Regional Impact Dashboard',
         'Dashboard publik untuk memvisualisasikan dampak pandemi terhadap ekonomi regional Indonesia berdasarkan data BPS dan Kemenkes.',
         'Python, Folium, Plotly Dash, PostgreSQL', '10K+ views, digunakan oleh 3 instansi pemerintah',
         'Public Data', 0, 5);

        INSERT INTO experience (company, role, period, description, sort_order) VALUES
        ('PT Teknologi Data Nusantara', 'Senior Data Analyst', 'Jan 2023 – Sekarang',
         'Memimpin tim analitik 4 orang, merancang arsitektur data warehouse, dan mengembangkan dashboard eksekutif untuk C-level. Bertanggung jawab atas pipeline data dari 15+ sumber.', 1),
        ('Startup Fintech XYZ', 'Data Analyst', 'Mar 2021 – Des 2022',
         'Membangun sistem scoring kredit berbasis machine learning, menganalisis perilaku pengguna aplikasi, dan mendukung product team dengan A/B testing.', 2),
        ('CV Konsultan Digital ABC', 'Junior Data Analyst', 'Jul 2020 – Feb 2021',
         'Melakukan analisis data klien UKM, membuat laporan visualisasi bulanan, dan membantu implementasi Google Analytics.', 3);

        INSERT INTO education (institution, degree, period, notes) VALUES
        ('Universitas Instutit Teknologi Bisnis AAS Indonesia', 'S1 Statistika', '2016 – 2020', 'IPK 3.72 / 4.00 · Cum Laude');

        INSERT INTO certifications (name, issuer, year, icon) VALUES
        ('Google Data Analytics Professional Certificate', 'Google / Coursera', 2023, 'bi-google'),
        ('Tableau Desktop Specialist',                     'Tableau',            2022, 'bi-bar-chart-line'),
        ('Microsoft Power BI Data Analyst (PL-300)',       'Microsoft',          2023, 'bi-microsoft'),
        ('Python for Data Science',                        'IBM / Coursera',     2021, 'bi-code-square');
    ");

    $pdo->exec("COMMIT;");
}
